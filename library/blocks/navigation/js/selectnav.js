/**
 * @preserve SelectNav.js (v. 0.1)
 * Converts your <ul>/<ol> navigation into a dropdown list for small screens
 * https://github.com/lukaszfiszer/selectnav.js
 */

window.selectnav = (function(){

"use strict";

  var selectnav = function(element,options){

  	if ( typeof element == 'string' )
   		element = document.getElementById(element);

    // return immediately if element doesn't exist
    if( ! element){
      return;
    }

    // return immediately if element is not a list
    if( ! islist(element) ){
      return;
    }

    // add a js class to <html> tag
    document.documentElement.className += " js";

    // retreive options and set defaults
    var o = options || {},

      activeclass = o.activeclass || 'active',
      autoselect = typeof(o.autoselect) === "boolean" ? o.autoselect : true,
      nested = typeof(o.nested) === "boolean" ? o.nested : true,
      indent = o.indent || "→",
      label = o.label || "- Navigation -";

    // insert the freshly created dropdown navigation after the existing navigation
    var nav = parselist(element);
    element.parentNode.insertBefore(nav, element.nextSibling);

    // autoforward on click
    if (nav.addEventListener) {
      nav.addEventListener('change',goTo);
    }
    if (nav.attachEvent) {
      nav.attachEvent('onchange', goTo);
    }

    return nav;

    function goTo(e){

      // Crossbrowser issues - http://www.quirksmode.org/js/events_properties.html
      var targ;
      if (!e) e = window.event;
      if (e.target) targ = e.target;
      else if (e.srcElement) targ = e.srcElement;
      if (targ.nodeType === 3) // defeat Safari bug
        targ = targ.parentNode; 

      if(targ.value) window.location.href = targ.value; 
    }

    function islist(list){
      var n = list.nodeName.toLowerCase();
      return (n === 'ul' || n === 'ol');
    }

    function id(nextId){
      for(var j=1; document.getElementById('selectnav'+j);j++);
      return (nextId) ? 'selectnav'+j : 'selectnav'+(j-1);
    }

    function parselist(list){
      var select = document.createElement('select');
      select.className = 'selectnav';
      select.id = id(true);

      if(label) {
        var labelOption = document.createElement('option');
        labelOption.value = '';
        labelOption.textContent = label;
        select.appendChild(labelOption);
      }

      appendOptions(list, select, 0);
      return select;
    }

    function appendOptions(list, select, depth) {
      var prefix = '';
      for(var prefixLevel = 0; prefixLevel < depth; prefixLevel++) {
        prefix += indent;
      }
      if(prefix) prefix += ' ';

      for(var i = 0; i < list.children.length; i++) {
        var link = list.children[i].children[0];
        if(typeof(link) === 'undefined') continue;

        var linkToGetText = jQuery(link).clone();
        linkToGetText.find('.sf-sub-indicator').remove();

        var option = document.createElement('option');
        option.value = link.href;
        option.textContent = prefix + linkToGetText.text();
        option.selected = activeclass && (
          link.className.search(activeclass) !== -1 ||
          link.parentNode.className.search(activeclass) !== -1
        );

        if(autoselect && !option.selected) {
          option.selected = link.href === document.URL;
        }
        select.appendChild(option);

        if(nested) {
          var subElement = list.children[i].children[1];
          if(subElement && islist(subElement)) {
            appendOptions(subElement, select, depth + 1);
          }
        }
      }
    }

  };

  return function (element,options) {
    selectnav(element,options);
  };


})();