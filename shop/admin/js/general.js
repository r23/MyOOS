
(function(window, document, $, undefined){

  if (typeof $ === 'undefined') { throw new Error('This application\'s JavaScript requires jQuery'); }

  $(function(){

    // Restore body classes
    // ----------------------------------- 
    var $body = $('body');
    new StateToggler().restoreState( $body );
    
    // enable settings toggle after restore
    $('#chk-fixed').prop('checked', $body.hasClass('layout-fixed') );
    $('#chk-collapsed').prop('checked', $body.hasClass('aside-collapsed') );
    $('#chk-boxed').prop('checked', $body.hasClass('layout-boxed') );
    $('#chk-float').prop('checked', $body.hasClass('aside-float') );
    $('#chk-hover').prop('checked', $body.hasClass('aside-hover') );

    // When ready display the offsidebar
    $('.offsidebar.hide').removeClass('hide');      

  }); // doc ready


})(window, document, window.jQuery);

// Start Bootstrap JS
// ----------------------------------- 

(function(window, document, $, undefined){

  $(function(){

    // POPOVER
    // ----------------------------------- 

    $('[data-toggle="popover"]').popover();

    // TOOLTIP
    // ----------------------------------- 

    $('[data-toggle="tooltip"]').tooltip({
      container: 'body'
    });

    // DROPDOWN INPUTS
    // ----------------------------------- 
    $('.dropdown input').on('click focus', function(event){
      event.stopPropagation();
    });

  });

})(window, document, window.jQuery);



// GLOBAL CONSTANTS
// ----------------------------------- 


(function(window, document, $, undefined){

  window.APP_COLORS = {
    'primary':                '#5d9cec',
    'success':                '#27c24c',
    'info':                   '#23b7e5',
    'warning':                '#ff902b',
    'danger':                 '#f05050',
    'inverse':                '#131e26',
    'green':                  '#37bc9b',
    'pink':                   '#f532e5',
    'purple':                 '#7266ba',
    'dark':                   '#3a3f51',
    'yellow':                 '#fad732',
    'gray-darker':            '#232735',
    'gray-dark':              '#3a3f51',
    'gray':                   '#dde6e9',
    'gray-light':             '#e4eaec',
    'gray-lighter':           '#edf1f2'
  };
  
  window.APP_MEDIAQUERY = {
    'desktopLG':             1200,
    'desktop':                992,
    'tablet':                 768,
    'mobile':                 480
  };

})(window, document, window.jQuery);


// MARKDOWN DOCS
// ----------------------------------- 


(function(window, document, $, undefined){

  $(function(){

    $('.flatdoc').each(function(){

      Flatdoc.run({
        
        fetcher: Flatdoc.file('documentation/readme.md'),

        // Setup custom element selectors (markup validates)
        root:    '.flatdoc',
        menu:    '.flatdoc-menu',
        title:   '.flatdoc-title',
        content: '.flatdoc-content'

      });

    });


  });

})(window, document, window.jQuery);

// FULLSCREEN
// ----------------------------------- 

(function(window, document, $, undefined){

  if ( typeof screenfull === 'undefined' ) return;

  $(function(){

    var $doc = $(document);
    var $fsToggler = $('[data-toggle-fullscreen]');

    // Not supported under IE
    var ua = window.navigator.userAgent;
    if( ua.indexOf("MSIE ") > 0 || !!ua.match(/Trident.*rv\:11\./) ) {
      $fsToggler.addClass('hide');
    }

    if ( ! $fsToggler.is(':visible') ) // hidden on mobiles or IE
      return;

    $fsToggler.on('click', function (e) {
        e.preventDefault();

        if (screenfull.enabled) {
          
          screenfull.toggle();
          
          // Switch icon indicator
          toggleFSIcon( $fsToggler );

        } else {
          console.log('Fullscreen not enabled');
        }
    });

    if ( screenfull.raw && screenfull.raw.fullscreenchange)
      $doc.on(screenfull.raw.fullscreenchange, function () {
          toggleFSIcon($fsToggler);
      });

    function toggleFSIcon($element) {
      if(screenfull.isFullscreen)
        $element.children('em').removeClass('fa-expand').addClass('fa-compress');
      else
        $element.children('em').removeClass('fa-compress').addClass('fa-expand');
    }

  });

})(window, document, window.jQuery);


/**
 * Collapse panels
 * [data-tool="panel-collapse"]
 *
 * Also uses browser storage to keep track
 * of panels collapsed state
 */
(function($, window, document) {
  'use strict';
  var panelSelector = '[data-tool="panel-collapse"]',
      storageKeyName = 'jq-panelState';

  // Prepare the panel to be collapsable and its events
  $(panelSelector).each(function() {
    // find the first parent panel
    var $this        = $(this),
        parent       = $this.closest('.panel'),
        wrapper      = parent.find('.panel-wrapper'),
        collapseOpts = {toggle: false},
        iconElement  = $this.children('i'),
        panelId      = parent.attr('id');
    
    // if wrapper not added, add it
    // we need a wrapper to avoid jumping due to the paddings
    if( ! wrapper.length) {
      wrapper =
        parent.children('.panel-heading').nextAll() //find('.panel-body, .panel-footer')
          .wrapAll('<div/>')
          .parent()
          .addClass('panel-wrapper');
      collapseOpts = {};
    }

    // Init collapse and bind events to switch icons
    wrapper
      .collapse(collapseOpts)
      .on('hide.bs.collapse', function() {
        setIconHide( iconElement );
        savePanelState( panelId, 'hide' );
        wrapper.prev('.panel-heading').addClass('panel-heading-collapsed');
      })
      .on('show.bs.collapse', function() {
        setIconShow( iconElement );
        savePanelState( panelId, 'show' );
        wrapper.prev('.panel-heading').removeClass('panel-heading-collapsed');
      });

    // Load the saved state if exists
    var currentState = loadPanelState( panelId );
    if(currentState) {
      setTimeout(function() { wrapper.collapse( currentState ); }, 0);
      savePanelState( panelId, currentState );
    }

  });

  // finally catch clicks to toggle panel collapse
  $(document).on('click', panelSelector, function () {
    
    var parent = $(this).closest('.panel');
    var wrapper = parent.find('.panel-wrapper');

    wrapper.collapse('toggle');

  });

  /////////////////////////////////////////////
  // Common use functions for panel collapse //
  /////////////////////////////////////////////
  function setIconShow(iconEl) {
    iconEl.removeClass('fa-plus').addClass('fa-minus');
  }

  function setIconHide(iconEl) {
    iconEl.removeClass('fa-minus').addClass('fa-plus');
  }

  function savePanelState(id, state) {
    var data = $.localStorage.get(storageKeyName);
    if(!data) { data = {}; }
    data[id] = state;
    $.localStorage.set(storageKeyName, data);
  }

  function loadPanelState(id) {
    var data = $.localStorage.get(storageKeyName);
    if(data) {
      return data[id] || false;
    }
  }


}(jQuery, window, document));


/**
 * Refresh panels
 * [data-tool="panel-refresh"]
 * [data-spinner="standard"]
 */
(function($, window, document){
  'use strict';
  var panelSelector  = '[data-tool="panel-refresh"]',
      refreshEvent   = 'panel.refresh',
      whirlClass     = 'whirl',
      defaultSpinner = 'standard';

  // method to clear the spinner when done
  function removeSpinner(){
    this.removeClass(whirlClass);
  }

  // catch clicks to toggle panel refresh
  $(document).on('click', panelSelector, function () {
      var $this   = $(this),
          panel   = $this.parents('.panel').eq(0),
          spinner = $this.data('spinner') || defaultSpinner
          ;

      // start showing the spinner
      panel.addClass(whirlClass + ' ' + spinner);

      // attach as public method
      panel.removeSpinner = removeSpinner;

      // Trigger the event and send the panel object
      $this.trigger(refreshEvent, [panel]);

  });


}(jQuery, window, document));



// SIDEBAR
// ----------------------------------- 


(function(window, document, $, undefined){

  var $win;
  var $html;
  var $body;
  var $sidebar;
  var mq;

  $(function(){

    $win     = $(window);
    $html    = $('html');
    $body    = $('body');
    $sidebar = $('.sidebar');
    mq       = APP_MEDIAQUERY;
    
    // AUTOCOLLAPSE ITEMS 
    // ----------------------------------- 

    var sidebarCollapse = $sidebar.find('.collapse');
    sidebarCollapse.on('show.bs.collapse', function(event){

      event.stopPropagation();
      if ( $(this).parents('.collapse').length === 0 )
        sidebarCollapse.filter('.in').collapse('hide');

    });
    
    // SIDEBAR ACTIVE STATE 
    // ----------------------------------- 
    
    // Find current active item
    var currentItem = $('.sidebar .active').parents('li');

    // hover mode don't try to expand active collapse
    if ( ! useAsideHover() )
      currentItem
        .addClass('active')     // activate the parent
        .children('.collapse')  // find the collapse
        .collapse('show');      // and show it

    // remove this if you use only collapsible sidebar items
    $sidebar.find('li > a + ul').on('show.bs.collapse', function (e) {
      if( useAsideHover() ) e.preventDefault();
    });

    // SIDEBAR COLLAPSED ITEM HANDLER
    // ----------------------------------- 


    var eventName = isTouch() ? 'click' : 'mouseenter' ;
    var subNav = $();
    $sidebar.on( eventName, '.nav > li', function() {

      if( isSidebarCollapsed() || useAsideHover() ) {

        subNav.trigger('mouseleave');
        subNav = toggleMenuItem( $(this) );

        // Used to detect click and touch events outside the sidebar          
        sidebarAddBackdrop();
      }

    });

    var sidebarAnyclickClose = $sidebar.data('sidebarAnyclickClose');

    // Allows to close
    if ( typeof sidebarAnyclickClose !== 'undefined' ) {

      $('.wrapper').on('click.sidebar', function(e){
        // don't check if sidebar not visible
        if( ! $body.hasClass('aside-toggled')) return;

        var $target = $(e.target);
        if( ! $target.parents('.aside').length && // if not child of sidebar
            ! $target.is('#user-block-toggle') && // user block toggle anchor
            ! $target.parent().is('#user-block-toggle') // user block toggle icon
          ) {
                $body.removeClass('aside-toggled');          
        }

      });
    }

  });

  function sidebarAddBackdrop() {
    var $backdrop = $('<div/>', { 'class': 'dropdown-backdrop'} );
    $backdrop.insertAfter('.aside').on("click mouseenter", function () {
      removeFloatingNav();
    });
  }

  // Open the collapse sidebar submenu items when on touch devices 
  // - desktop only opens on hover
  function toggleTouchItem($element){
    $element
      .siblings('li')
      .removeClass('open')
      .end()
      .toggleClass('open');
  }

  // Handles hover to open items under collapsed menu
  // ----------------------------------- 
  function toggleMenuItem($listItem) {

    removeFloatingNav();

    var ul = $listItem.children('ul');
    
    if( !ul.length ) return $();
    if( $listItem.hasClass('open') ) {
      toggleTouchItem($listItem);
      return $();
    }

    var $aside = $('.aside');
    var $asideInner = $('.aside-inner'); // for top offset calculation
    // float aside uses extra padding on aside
    var mar = parseInt( $asideInner.css('padding-top'), 0) + parseInt( $aside.css('padding-top'), 0);

    var subNav = ul.clone().appendTo( $aside );
    
    toggleTouchItem($listItem);

    var itemTop = ($listItem.position().top + mar) - $sidebar.scrollTop();
    var vwHeight = $win.height();

    subNav
      .addClass('nav-floating')
      .css({
        position: isFixed() ? 'fixed' : 'absolute',
        top:      itemTop,
        bottom:   (subNav.outerHeight(true) + itemTop > vwHeight) ? 0 : 'auto'
      });

    subNav.on('mouseleave', function() {
      toggleTouchItem($listItem);
      subNav.remove();
    });

    return subNav;
  }

  function removeFloatingNav() {
    $('.sidebar-subnav.nav-floating').remove();
    $('.dropdown-backdrop').remove();
    $('.sidebar li.open').removeClass('open');
  }

  function isTouch() {
    return $html.hasClass('touch');
  }
  function isSidebarCollapsed() {
    return $body.hasClass('aside-collapsed');
  }
  function isSidebarToggled() {
    return $body.hasClass('aside-toggled');
  }
  function isMobile() {
    return $win.width() < mq.tablet;
  }
  function isFixed(){
    return $body.hasClass('layout-fixed');
  }
  function useAsideHover() {
    return $body.hasClass('aside-hover');
  }

})(window, document, window.jQuery);



// SLIMSCROLL
// ----------------------------------- 

(function(window, document, $, undefined){

  $(function(){

    $('[data-scrollable]').each(function(){

      var element = $(this),
          defaultHeight = 250;
      
      element.slimScroll({
          height: (element.data('height') || defaultHeight)
      });
      
    });
  });

})(window, document, window.jQuery);


// TOGGLE STATE
// ----------------------------------- 

(function(window, document, $, undefined){

  $(function(){

    var $body = $('body');
        toggle = new StateToggler();

    $('[data-toggle-state]')
      .on('click', function (e) {
        // e.preventDefault();
        e.stopPropagation();
        var element = $(this),
            classname = element.data('toggleState'),
            noPersist = (element.attr('data-no-persist') !== undefined);

        if(classname) {
          if( $body.hasClass(classname) ) {
            $body.removeClass(classname);
            if( ! noPersist)
              toggle.removeState(classname);
          }
          else {
            $body.addClass(classname);
            if( ! noPersist)
              toggle.addState(classname);
          }
          
        }
        // some elements may need this when toggled class change the content size
        // e.g. sidebar collapsed mode and jqGrid
        $(window).resize();

    });

  });

  // Handle states to/from localstorage
  window.StateToggler = function() {

    var storageKeyName  = 'jq-toggleState';

    // Helper object to check for words in a phrase //
    var WordChecker = {
      hasWord: function (phrase, word) {
        return new RegExp('(^|\\s)' + word + '(\\s|$)').test(phrase);
      },
      addWord: function (phrase, word) {
        if (!this.hasWord(phrase, word)) {
          return (phrase + (phrase ? ' ' : '') + word);
        }
      },
      removeWord: function (phrase, word) {
        if (this.hasWord(phrase, word)) {
          return phrase.replace(new RegExp('(^|\\s)*' + word + '(\\s|$)*', 'g'), '');
        }
      }
    };

    // Return service public methods
    return {
      // Add a state to the browser storage to be restored later
      addState: function(classname){
        var data = $.localStorage.get(storageKeyName);
        
        if(!data)  {
          data = classname;
        }
        else {
          data = WordChecker.addWord(data, classname);
        }

        $.localStorage.set(storageKeyName, data);
      },

      // Remove a state from the browser storage
      removeState: function(classname){
        var data = $.localStorage.get(storageKeyName);
        // nothing to remove
        if(!data) return;

        data = WordChecker.removeWord(data, classname);

        $.localStorage.set(storageKeyName, data);
      },
      
      // Load the state string and restore the classlist
      restoreState: function($elem) {
        var data = $.localStorage.get(storageKeyName);
        
        // nothing to restore
        if(!data) return;
        $elem.addClass(data);
      }

    };
  };

})(window, document, window.jQuery);



(function(window, document, $, undefined){

  $(function(){


    // DATETIMEPICKER
    // ----------------------------------- 

    $('#datetimepicker1').datetimepicker({
	  format: 'YYYY-MM-DD',
      icons: {
          time: 'fa fa-clock-o',
          date: 'fa fa-calendar',
          up: 'fa fa-chevron-up',
          down: 'fa fa-chevron-down',
          previous: 'fa fa-chevron-left',
          next: 'fa fa-chevron-right',
          today: 'fa fa-crosshairs',
          clear: 'fa fa-trash'
        }
    });

  });

})(window, document, window.jQuery);



// Custom jQuery
// ----------------------------------- 

(function(window, document, $, undefined){

  $(function(){

    // document ready

  });

})(window, document, window.jQuery);