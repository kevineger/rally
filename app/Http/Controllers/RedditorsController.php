<?php

namespace App\Http\Controllers;

use App\Repositories\RedditorRepository;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpRAW\phpRAW as phpRAW;
use App\Http\Requests;


class RedditorsController extends Controller {

    /*
     * @var App\Repositories\RedditorRepository
     */
    protected $redditor;

    public function __construct(RedditorRepository $redditor)
    {
        $this->redditor = $redditor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('redditor.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = $request->redditor;

        $subreddits = $this->redditor->getUserSubmitted($user)->getSubredditsList();
        $redditor = $this->redditor->getRedditor($user);
        $redditor_for = Carbon::createFromTimestamp($redditor->created)->diffForHumans();
        $top_up_votes = $this->redditor->getUserSubmitted($user)->getTopUpVotes();
        $top_down_votes = $this->redditor->getUserSubmitted($user)->getTopDownVotes();
        $last_submission = $this->redditor->getUserSubmitted($user)->getLastSubmission();
        $submissions = $this->redditor->getUserSubmitted($user)->getSubmissions();
        $average_karma = $this->redditor->getUserSubmitted($user)->getAverageSubmissionKarma();

        return response()->view('redditor.show', [
            'redditor'        => $redditor,
            'redditor_for'    => $redditor_for,
            'subreddits'      => $subreddits,
            'top_up_votes'    => $top_up_votes,
            'top_down_votes'  => $top_down_votes,
            'last_submission' => $last_submission,
            'submissions'     => $submissions,
            'average_karma'   => $average_karma
        ]);
    }
}
