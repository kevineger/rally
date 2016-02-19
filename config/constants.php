<?php

return [
    // Best Hours to post on reddit, by subreddit. Only hours with more than 100 posts with more than 3000 points.
    // Credit: u/fhoffa
    'best_hours'         => 'SELECT GROUP_CONCAT(STRING(sub_hour)) as hours, subreddit, SUM(num_gte_3000) total FROM ( SELECT HOUR(SEC_TO_TIMESTAMP(created - 60*60*5)) as sub_hour, SUM(score >= 3000) as num_gte_3000, subreddit, RANK() OVER(PARTITION BY subreddit ORDER BY num_gte_3000 DESC) rank, FROM [fh-bigquery:reddit_posts.full_corpus_201509] WHERE YEAR(SEC_TO_TIMESTAMP(created))=2015 GROUP BY sub_hour, subreddit HAVING num_gte_3000 > 100 ) WHERE rank<=3 GROUP BY subreddit ORDER BY total DESC',

    // Given a word in the title, probability of getting a score > 300 by subreddit.
    // Credit: u/fhoffa
    'prob_post'          => 'SELECT subreddit, num_gte, prob, GROUP_CONCAT_UNQUOTED(UNIQUE(SPLIT(title, \' \'))) WITHIN RECORD title_words FROM ( SELECT SUM(score >= 300) as num_gte, SUM(score >= 300)/COUNT(*) as prob, subreddit, GROUP_CONCAT_UNQUOTED(REGEXP_REPLACE(LOWER(title), r\'[!,\.\"();\[\];:?]\',\' \'), \' \') title, FROM [fh-bigquery:reddit_posts.full_corpus_201509] WHERE YEAR(SEC_TO_TIMESTAMP(created))=2015 AND MONTH(SEC_TO_TIMESTAMP(created))>4 AND REGEXP_MATCH(LOWER(title), r\'\b\'+\'clinton\'+r\'\b\') GROUP BY subreddit ) WHERE num_gte>5 ORDER BY prob DESC',

    // Time usage of specified subreddits over time.
    'time_usage'         => 'SELECT RIGHT(\'0\'+STRING(peak),2)+\'-\'+subreddit, hour, c FROM ( SELECT subreddit, hour, c, MIN(IF(rank=1,hour,null)) OVER(PARTITION BY subreddit) peak FROM ( SELECT subreddit, HOUR(SEC_TO_TIMESTAMP(created_utc)) hour, COUNT(*) c, ROW_NUMBER() OVER(PARTITION BY subreddit ORDER BY c ) rank FROM [fh-bigquery:reddit_comments.2015_08] WHERE subreddit IN (%subreddits) AND score>2 GROUP BY 1, 2 ) ) ORDER BY 1,2',

    // Clustering Subreddits by most recent comments
    'cluster_info'       => 'SELECT author, link_id, COUNT(id) as cnt FROM ( SELECT author, link_id, id FROM [fh-bigquery:reddit_comments.2016_01] WHERE subreddit = (%subreddits) AND author != \'[deleted]\' ORDER BY created_utc DESC LIMIT 10000 ) GROUP BY author, link_id ORDER BY author',

    // Clustering Subreddits by most recent comments on posts
    'cluster_info_posts' => 'SELECT author, link_id, COUNT(link_id) as cnt FROM ( SELECT * FROM [fh-bigquery:reddit_comments.2016_01], [fh-bigquery:reddit_comments.2015_12], [fh-bigquery:reddit_comments.2015_11] ) WHERE link_id IN ( SELECT posts.name FROM [fh-bigquery:reddit_posts.full_corpus_201512] AS posts WHERE posts.subreddit = (%subreddits) AND posts.num_comments > 0 ORDER BY posts.created_utc DESC LIMIT 300 ) AND author != \'[deleted]\' AND author != \'Lamont-Cranston\' GROUP BY author, link_id ORDER BY author',


    // Retrive subreddits from bigqueryu (verify they are there) - Usefull for testing
    'subreddits'         => 'SELECT subr FROM [fh-bigquery:reddit.subreddits] GROUP BY subr'
];