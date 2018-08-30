<?php

namespace App\BusinessLogicLayer\gamification;


use App\BusinessLogicLayer\QuestionnaireResponseReferralManager;

class InfluencerBadge extends GamificationBadge {

    private $questionnaireResponseReferralManager;

    public function __construct(QuestionnaireResponseReferralManager $questionnaireResponseReferralManager, int $userId) {
        $this->questionnaireResponseReferralManager = $questionnaireResponseReferralManager;
        $pointsPerAction = 5;
        $this->badgeID = GamificationBadgeIdsEnum::INFLUENCER_BADGE_ID;
        $this->color = '#f44336';
        $numberOfActionsPerformed = $this->questionnaireResponseReferralManager->getQuestionnaireReferralsForUser($userId)->count();
        parent::__construct("Influencer",
            "influencer.png",
            "In order to gain this badge, people have to respond to your social posts and contribute!",
            $numberOfActionsPerformed, $userId, $pointsPerAction);
    }

    public function getBadgeMessageForLevel() {
        return 'You have invited ' . $this->numberOfActionsPerformed . ' people to answer';
    }

    public function getEmailBody() {
        if($this->level == 1)
            return 'You have also unlocked a new badge:';
        return 'You are a Level <b>' . $this->level . '</b> Influencer! Keep Going!';
    }
}