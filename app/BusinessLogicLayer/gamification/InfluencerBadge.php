<?php

namespace App\BusinessLogicLayer\gamification;


use App\BusinessLogicLayer\QuestionnaireResponseReferralManager;

class InfluencerBadge extends GamificationBadge {

    private $questionnaireResponseReferralManager;

    public function __construct(QuestionnaireResponseReferralManager $questionnaireResponseReferralManager, int $userId) {
        $this->questionnaireResponseReferralManager = $questionnaireResponseReferralManager;
        $pointsPerAction = 5;
        $this->badgeID = GamificationBadgeIdsEnum::INFLUENCER_BADGE_ID;
        $numberOfActionsPerformed = $this->questionnaireResponseReferralManager->getQuestionnaireReferralsForUser($userId)->count();
        parent::__construct("Influencer",
            "influencer.png",
            "In order to gain this badge, people have to respond to your social posts and contribute!",
            $numberOfActionsPerformed, $userId, $pointsPerAction);
    }

    public function getBadgeMessageForLevel() {
        return 'You have invited ' . $this->numberOfActionsPerformed . ' people to answer';
    }

    public function getHTMLForCompletedAction() {
        return (object)[
            'badgeName' => 'Influencer (Level ' . $this->level . ')',
            'html' =>
                '<p>Thank you for inviting users to contribute!</p><p>The Influencer badge now belongs to you!</p>
                        <img class="gamification-badge" src="' . asset('images/badges/influencer.png') . '">
                        <p>Influencer <span class="level">(Level ' . $this->level . ')</span></p>'
        ];
    }

    public function getEmailBody() {
        // TODO: Implement getEmailBody() method.
    }
}