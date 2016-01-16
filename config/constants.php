<?php

return [
  'some_query' => 'SELECT DAYOFWEEK(SEC_TO_TIMESTAMP(created - 60*60*5)) as sub_dayofweek, HOUR(SEC_TO_TIMESTAMP(created - 60*60*5)) as sub_hour, SUM(IF(score >= 3000, 1, 0)) as num_gte_3000, FROM [fh-bigquery:reddit_posts.full_corpus_201509] GROUP BY sub_dayofweek, sub_hour ORDER BY sub_dayofweek, sub_hour'
];