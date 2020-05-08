jQuery(($) => {
  window.loaded_posts = [];
  if ($('body').hasClass('page-template-page-landing')) {
    /**
         * I limited the scope of this because this solves an issue only present on the news page whereby the first load
         * more request would return the last post currently on the page so we ended up with a duplicate post. All subsequent
         * load more requests work fine as do the load mores on all other pages.
         */
    $('article').each((i, post) => {
      const post_id = $(post).data('postid');
      if (typeof post_id !== 'undefined') {
        window.loaded_posts.push(post_id);
      }
    });
  }
});
