(function ($) {
  Drupal.behaviors.ldbaseCitationAddLinkBehavior = {
    attach: function (context, settings) {
      $(context).find('div.block-ldbase-citation-display-block > div.content').once('add-paragraph')
        .prepend('<span class="modal-close">Close X</span>');
    }
  };
}) (jQuery);

(function ($) {
  Drupal.behaviors.ldbaseCitationsBehavior = {
    attach: function (context, settings) {
      $(context).find('.modal-close').once('closeModalBehavior').click(function () {
        $(context).find('.block-ldbase-citation-display-block').css("display","none");
        $(context).find('.sidebar-first').css("display","block");
        $(context).find('.header-container').css("z-index","499");
      });
    }
  };
}) (jQuery);


// Get the modal
var modal_list = document.getElementsByClassName("block-ldbase-citation-display-block");
var modal = modal_list[0];

// Get the left sidebar
var sidebar_list = document.getElementsByClassName("sidebar-first");
var sidebar = sidebar_list[0];

// Get the header container
var header_list = document.getElementsByClassName("header-container");
var header = header_list[0];

// Get the button that opens the modal
var btn = document.getElementById("open-citation-modal");

// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
  sidebar.style.display = "none";
  header.style.zIndex = "0";
}
