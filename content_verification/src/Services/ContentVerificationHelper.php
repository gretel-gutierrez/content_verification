<?php

namespace Drupal\content_verification\Services;

class ContentVerificationHelper {

    /**
     * Calculates the Flesch-Kincaid readability score for the text.
     *
     * Uses an estimated method based on word, sentence, and syllable counts.
     */
    public function calculateFleschKincaid($text) {
        $word_count = str_word_count(strip_tags($text));
        $sentence_count = preg_match_all('/[.!?]/', strip_tags($text));
        $syllable_count = $this->countSyllables(strip_tags($text));

        if ($word_count > 0 && $sentence_count > 0) {
            $flesch_kincaid_score = 206.835 - (1.015 * ($word_count / $sentence_count)) - (84.6 * ($syllable_count / $word_count));
            return $flesch_kincaid_score;
        }

        return 0;
    }

    /**
     * Counts the approximate number of syllables in the text.
     */
    public function countSyllables($text) {
        $vowel_groups = preg_match_all('/[aeiouy]+/i', $text, $matches);
        return $vowel_groups;
    }
}