<?php

namespace App\Services;

class CaptchaService
{
    /**
     * Generate a new math CAPTCHA challenge and store the answer in session.
     */
    public function generateChallenge(): array
    {
        $num1 = rand(2, 9);
        $num2 = rand(1, 9);
        $operators = ['+', 'x'];
        $op = $operators[rand(0, 1)];

        if ($op === '+') {
            $answer = $num1 + $num2;
            $question = "{$num1} + {$num2}";
        } else {
            $answer = $num1 * $num2;
            $question = "{$num1} × {$num2}";
        }

        session([
            'captcha_question' => $question,
            'captcha_answer' => $answer,
        ]);

        return [
            'question' => $question,
            'answer' => $answer,
        ];
    }

    /**
     * Get current challenge question or generate if missing.
     */
    public function getQuestion(): string
    {
        if (!session()->has('captcha_answer') || !session()->has('captcha_question')) {
            $challenge = $this->generateChallenge();
            return $challenge['question'];
        }

        return session('captcha_question');
    }

    /**
     * Verify user submitted CAPTCHA answer.
     */
    public function verify(mixed $userAnswer): bool
    {
        if (!session()->has('captcha_answer')) {
            return false;
        }

        $correctAnswer = (int) session('captcha_answer');
        $submitted = (int) trim((string)$userAnswer);

        // Regenerate new challenge after verification attempt
        $this->generateChallenge();

        return $submitted === $correctAnswer;
    }
}
