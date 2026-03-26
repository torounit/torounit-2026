<?php

/**
 * Title: List of posts
 * Slug: torounit-2026/posts-list
 * Categories: query, posts
 * Block Types: core/query
 * Description: A list of posts
 *
 */
?>

<!-- wp:query {"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"metadata":{"categories":["posts","posts"],"patternName":"torounit-2026/posts-list","name":"List of Posts"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
    <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group"><!-- wp:post-title {"level":3,"isLink":true} /-->

        <!-- wp:post-date {"isLink":true,"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"style":{"elements":{"link":{"color":{"text":"var:preset|color|dim"}}}},"textColor":"dim"} /-->
    </div>
    <!-- /wp:group -->
    <!-- /wp:post-template -->
</div>
<!-- /wp:query -->