(function ($, Drupal) {
  Drupal.behaviors.ldbaseCitationAddLinkBehavior = {
    attach: function (context, settings) {
      $(context).find('div.block-ldbase-citation-display-block > div.content').once('add-paragraph')
        .prepend('<span class="modal-close">X</span>');
    }
  };


  Drupal.behaviors.ldbaseCloseCitationsBehavior = {
    attach: function (context, settings) {
      $(context).find('.modal-close').once('closeModalBehavior').click(function () {
        $(context).find('.block-ldbase-citation-display-block').css("display","none");
        $(context).find('.sidebar-second').css("display","block");
        $(context).find('.header-container').css("z-index","499");
      });
    }
  };

  Drupal.behaviors.openCitation = {
    attach: function (context, settings) {
      $('a#open-citation-modal', context).click(function () {
        $('.block-ldbase-citation-display-block').css('display', 'block');
        $('.sidebar-second').css("display","none");
        $('.header-container').css("z-index", "0");
      })
    }
  };

}) (jQuery, Drupal);
