<?php

namespace App\Jobs;

use App\Models\Candidate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class ParseResumeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(public int $candidateId) {}

    public function handle(): void
    {
        $candidate = Candidate::find($this->candidateId);

        if (! $candidate || ! $candidate->resume_path) {
            return;
        }

        // Only PDFs are parsed for text; DOCX parsing would need a different library
        if (! str_ends_with($candidate->resume_path, '.pdf')) {
            return;
        }

        try {
            $fileContents = Storage::disk('s3')->get($candidate->resume_path);

            $tempPath = tempnam(sys_get_temp_dir(), 'resume') . '.pdf';
            file_put_contents($tempPath, $fileContents);

            $parser = new Parser();
            $pdf = $parser->parseFile($tempPath);
            $text = $pdf->getText();

            unlink($tempPath);

            preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $emailMatch);
            preg_match('/(\+?\d[\d\s\-().]{8,}\d)/', $text, $phoneMatch);
            preg_match('/linkedin\.com\/in\/[a-zA-Z0-9-]+/', $text, $linkedinMatch);

            $knownSkills = ['PHP', 'Laravel', 'JavaScript', 'Python', 'React', 'Vue', 'MySQL', 'Docker', 'AWS', 'Git'];
            $foundSkills = array_values(array_filter($knownSkills, fn ($skill) => stripos($text, $skill) !== false));

            $candidate->update([
                'parsed_data' => [
                    'detected_email' => $emailMatch[0] ?? null,
                    'detected_phone' => $phoneMatch[0] ?? null,
                    'detected_linkedin' => $linkedinMatch[0] ?? null,
                    'detected_skills' => $foundSkills,
                    'text_length' => strlen($text),
                ],
            ]);
        } catch (\Exception $e) {
            $candidate->update([
                'parsed_data' => ['error' => 'Could not parse resume: ' . $e->getMessage()],
            ]);
        }
    }
}
