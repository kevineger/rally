<?php

namespace App\Http\Controllers;

use App\Repositories\RedditorRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use phpRAW\phpRAW as phpRAW;
use App\Http\Requests;
use Carbon\Carbon;


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
        return response()->view('redditor.index', ['tagline' => 'Look up a specific redditor']);
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
        $average_submission_karma = $this->redditor->getUserSubmitted($user)->getAverageSubmissionKarma();
        $top_comment = $this->redditor->getUserSubmitted($user)->getTopComment();
        $worst_comment = $this->redditor->getUserSubmitted($user)->getWorstComment();
        $total_comments = $this->redditor->getUserSubmitted($user)->getTotalComments();
        $average_comment_karma = $this->redditor->getUserSubmitted($user)->getAverageCommentKarma();
        $active_hours = $this->redditor->getUserSubmitted($user)->activeHours();

        return response()->view('redditor.show', [
            'tagline'                 => 'An in deapth creep of ' . $redditor->name,
            'redditor'                 => $redditor,
            'redditor_for'             => $redditor_for,
            'subreddits'               => $subreddits,
            'top_up_votes'             => $top_up_votes,
            'top_down_votes'           => $top_down_votes,
            'last_submission'          => $last_submission,
            'submissions'              => $submissions,
            'average_submission_karma' => $average_submission_karma,
            'top_comment'              => $top_comment,
            'worst_comment'            => $worst_comment,
            'total_comments'           => $total_comments,
            'average_comment_karma'    => $average_comment_karma,
            'active_hours'             => $active_hours
        ]);
    }
}
