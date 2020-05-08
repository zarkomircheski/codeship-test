<?php
/*
Template Name: Video
*/
?>
<?php get_header(); ?>

<div class="jw-video">
  <h1 class="jw-video-name'">Video</h1>
  <div class="jw-video-featured-wrapper">
    <div class="player">
      <div id="jw-video-featured"></div>
    </div>
    <div id="player-playlist" class="playlist-wrapper"></div>
  </div>

  <template id="jw-video-playlist">
    <p class="coming-up">Coming Up</p>
    <ul class="playlist">
      {{#each playlist}}
      <li class="playlist-item">
        <a
          class="playlist-item-link{{#if @first}} active{{/if}}"
          href="{{link}}"
          data-mediaid="{{mediaid}}"
        >
          <div class="playlist-thumbnail-wrapper">
            <span class="playlist-duration">00:{{duration}}</span>
            <div class="playlist-item-playing">Now Playing</div>
            <img class="playlist-thumbnail" src="{{image}}" />
          </div>
          <p>{{title}}</p>
        </a>
      </li>
      {{/each}}
    </ul>
  </template>
</div>

<?php get_footer(); ?>