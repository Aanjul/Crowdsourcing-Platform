<?php

namespace App\BusinessLogicLayer\gamification;

use App\BusinessLogicLayer\UserQuestionnaireShareManager;

class CommunicatorBadge extends GamificationBadge {

    private $questionnaireShareManager;

    public function __construct(UserQuestionnaireShareManager $questionnaireShareManager, $userId) {
        $this->questionnaireShareManager = $questionnaireShareManager;
        $this->badgeID = GamificationBadgeIdsEnum::COMMUNICATOR_BADGE_ID;
        $this->color = '#4CAF50';
        $numberOfActionsPerformed = $this->questionnaireShareManager->getQuestionnairesSharedByUser($userId)->count();
        parent::__construct("Communicator",
            "communicator.png",
            "Gain this badge, by inviting more people to participate. Share to Facebook and Twitter!",
            $numberOfActionsPerformed);
    }

    protected function getBadgeMessageForLevel() {
        return 'You have shared ' . $this->numberOfActionsPerformed . ' questionnaires';
    }

    public function getEmailBody() {
        // TODO: Implement getEmailBody() method.
    }
}