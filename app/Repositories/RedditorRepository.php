<?php

namespace App\Repositories;

use phpRAW\phpRAW as phpRAW;

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
     * @return $this
     */
    public function getUserSubmitted($user)
    {
        // If the user submitted data is for a different user, get the correct data.
        if ($this->user != $user)
        {
            $this->user_submitted = $this->phpraw->getUserSubmitted($user);
            $this->user = $user;
        }

        return $this;
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
     * Get a list of all subreddits a user has posted to.
     *
     * @return array
     */
    public function getSubredditsList()
    {
        $subreddit_post_count = array();

        // Count the subreddits
        foreach ($this->user_submitted->data->children as $listing)
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
        $ups = array();
        foreach ($this->user_submitted->data->children as $key => $listing)
        {
            $ups[$key] = $listing->data->ups;
        }

        return $this->user_submitted->data->children[array_search(max($ups), $ups)];
    }

    /**
     * Return the submission with the highest amount of down votes.
     *
     * @return mixed
     */
    public function getTopDownVotes()
    {
        $downs = array();
        foreach ($this->user_submitted->data->children as $key => $listing)
        {
            $downs[$key] = $listing->data->downs;
        }

        return $this->user_submitted->data->children[array_search(max($downs), $downs)];
    }

    /**
     * Return the user's last submission.
     *
     * @return mixed
     */
    public function getLastSubmission()
    {
        return $this->user_submitted->data->children[0]->data;
    }

    /**
     * Get the user's submissions.
     *
     * @return mixed
     */
    public function getSubmissions()
    {
        return $this->user_submitted->data->children;
    }

    public function getAverageSubmissionKarma()
    {
        $total = 0;
        foreach ($this->getSubmissions() as $submission)
        {
            $total += $submission->data->score;
        }

        return $total / count($this->getSubmissions());
    }

}