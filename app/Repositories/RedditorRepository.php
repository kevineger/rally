<?php

namespace App\Repositories;

use phpRAW\phpRAW as phpRAW;
use Carbon\Carbon;
//use DateTimeZone;
//use DateTime;

class RedditorRepository {

    protected $phpraw;
    private $user;
    protected $user_submitted;


    public function __construct()
    {
        $this->phpraw = new phpRAW();
    }

    /**
     * Get the User Submitted data from the API.
     *
     * @param $user
     * @param int $limit
     * @param null $after
     * @return $this
     */
    public function getUserSubmitted($user, $limit = 25, $after = null)
    {
        // If getting info on a new user or adding a page
        if ($this->user != $user || $after)
        {
            $this->user = $user;
            error_log("Adding Page");
            $cur_page = $this->phpraw->getUserOverview($user, null, null, $limit, $after);
            $this->appendSubmissions($cur_page->data->children);
            if ($cur_page->data->after)
            {
                $this->getUserSubmitted($user, $limit, $cur_page->data->after);
            }
        }

        return $this;
    }

    /**
     * Add submission data to global $user_submitted
     * @param $children
     */
    private function appendSubmissions($children)
    {
        if ($this->user_submitted == null)
        {
            $this->user_submitted = $children;
        } else
        {
            // Push each of the submissions on the the array
            foreach ($children as $child)
                array_push($this->user_submitted, $child);
        }
    }

    /**
     * Get about a user.
     *
     * @param $user
     * @return mixed
     */
    public function getRedditor($user)
    {
        return $this->phpraw->getUser($user)->data;
    }

    /**
     * Helper function to get the top value of the specified attribute.
     *
     * t1_ : Comment
     * t2_ : Account
     * t3_ : Link
     * t4_ : Message
     * t5_ : Subreddit
     * t6_ : Award
     * t8_ : PromoCampaign
     *
     * @param string $kind
     * @param $attr
     * @param bool|true $max
     */
    public function getTop($kind = "t3", $attr, $max = true)
    {
        $attribute = array();
        foreach ($this->user_submitted as $key => $listing)
        {
            if ($listing->kind == $kind)
                $attribute[$key] = $listing->data->$attr;
        }

        if ($max)
        {
            return $this->user_submitted[array_search(max($attribute), $attribute)];
        } else
        {
            return $this->user_submitted[array_search(min($attribute), $attribute)];
        }
    }

    /**
     * Get a list and count of all subreddits a user has posted to (link and comments).
     *
     * @return array
     */
    public function getSubredditsList()
    {
        $subreddit_post_count = array();

        // Count the subreddits
        foreach ($this->user_submitted as $listing)
        {
            if (!isset($subreddit_post_count[$listing->data->subreddit]))
            {
                $subreddit_post_count[$listing->data->subreddit] = 0;
            }
            $subreddit_post_count[$listing->data->subreddit]++;

        }

        return $subreddit_post_count;
    }

    /**
     * Return the submission with the highest amount of up votes.
     *
     * @return mixed
     */
    public function getTopUpVotes()
    {
        return $this->getTop('t3', 'ups');
    }

    /**
     * Return the submission with the highest amount of down votes.
     *
     * @return mixed
     */
    public function getTopDownVotes()
    {
        return $this->getTop('t3', 'downs');
    }

    /**
     * Return the user's last submission.
     *
     * @return mixed
     */
    public function getLastSubmission()
    {
        return $this->user_submitted[0]->data;
    }

    /**
     * Get the user's submissions.
     *
     * @return mixed
     */
    public function getSubmissions()
    {
        return $this->user_submitted;
    }

    /**
     * Helper to get a user's average karma of specified type.
     *
     * @param $kind
     * @return float
     */
    public function getAverageSubmission($kind)
    {
        $total = 0;
        $count = 0;
        foreach ($this->getSubmissions() as $submission)
        {
            if ($submission->kind == $kind)
            {
                $count++;
                $total += $submission->data->score;
            }
        }

        return $total / $count;
    }

    /**
     * Get a user's average submission karma.
     *
     * @return float
     */
    public function getAverageSubmissionKarma()
    {
        return $this->getAverageSubmission('t3');
    }

    /**
     * Get the user's top comment (highest upvotes).
     *
     * @return mixed
     */
    public function getTopComment()
    {
        return $this->getTop('t1', 'score');
    }

    /**
     * Get the user's bottom comment (highest downvotes).
     *
     * @return mixed
     */
    public function getWorstComment()
    {
        return $this->getTop('t1', 'score', false);
    }

    /**
     * Get the user's total number of comments.
     *
     * @return int
     */
    public function getTotalComments()
    {
        $total = 0;
        foreach ($this->getSubmissions() as $submission)
        {
            if ($submission->kind == 't1')
                $total++;
        }

        return $total;
    }

    /**
     * Get a user's average comment karma.
     *
     * @return float
     */
    public function getAverageCommentKarma()
    {
        return $this->getAverageSubmission('t1');
    }

    public function activeHours()
    {
        $hours = array_fill(0,24,0);
        foreach ($this->getSubmissions() as $submission)
        {
            $time = Carbon::createFromTimestampUTC($submission->data->created_utc);
//            $time->timezone = new DateTimeZone('America/Vancouver');
            $hour = $time->hour;
            $hours[$hour]++;
        }

        return $hours;
    }

}