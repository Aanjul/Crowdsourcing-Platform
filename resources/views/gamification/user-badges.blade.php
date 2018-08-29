@push('css')
    <link rel="stylesheet" href="{{ asset('dist/css/badges.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/next-step.css') }}">
@endpush

<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title">
            @if ($badgesVM->numOfBadges ==0)
                You don't have any badges, yet!
            @else
                You have <span class="numOfBadges">{{ $badgesVM->numOfBadges }}</span>
                badge{{ $badgesVM->numOfBadges == 1 ? '' : 's' }} so far
                {{--    (<span class="points">{{$badgesVM->totalPoints}}</span> reputation points)--}}
            @endif
        </h3>
    </div>
    <div class="box-body">
        <div class="text-center badges-container row">

            @foreach($badgesVM->badgesWithLevelsList as $badge)
                <div class="col-sm-4 badgeContainer" data-toggle="tooltip"
                     title="{{ $badge->statusMessage }}">
                    <div class="col-sm-12 gamification-badge {{ $badge->level == 0 ? 'locked' : 'unlocked' }}">
                        <div class="col-sm-12 badgeImg">
                            <img class="badgeImg" src="{{asset("images/badges/" . $badge->badgeImageName)}}">
                        </div>
                        <div class="col-sm-12">
                            <h4 class="align-middle badgeName">{{ $badge->badgeName }}</h4>
                            @if($badge->level)
                                <h6 class="align-middle badgeLevel">Level: <span
                                            class="points">{{ $badge->level }}</span></h6>
                                <h5 class="align-middle badgeMessage">{{ $badge->badgeMessage }}</h5>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>