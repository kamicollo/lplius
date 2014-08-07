var LplusElements = {
	elements: new Array,
	css: false
};
			
window.onload = initiate_lplus_creation;

//scans through the anchors in the doc and initiates their conversion into actual widgets
//also, initiates stylesheet insertion
function initiate_lplus_creation() {
	var elements = getElementsByClassName(document, 'lplius-widget');
	
	if (elements.length > 0) {
		if (LplusElements.css !== true) {
			create_lplus_css();
			LplusElements.css = true;
		}		
	}
	
	for (var i = 0; i < elements.length; i++) {
		LplusElements.elements[i] = elements[i];
		parse_lplus_anchors(elements[i], i);
	}
};
			
//inserts a remote lplius.lt stylesheet
function create_lplus_css() {	
	if (document.createStyleSheet) {
		//for IE
		document.createStyleSheet(website_base_url + 'valdiklis/style.css?v=2');
	}
	else {
		//for others
		element = document.createElement("link");
		element.setAttribute("rel", "stylesheet");
		element.setAttribute("type", "text/css");
		element.setAttribute("href", website_base_url + "valdiklis/style.css?v=2");
		document.getElementsByTagName("head")[0].appendChild(element);
	}
}						
			
//initiates the JSONP requests
function parse_lplus_anchors(element, i) {								
	var width = element.getAttribute('data-width') || 250;
	var id = element.getAttribute('data-id');	
	if (id == null) return;
		
	var src = "id=" + id + "&width=" + width + '&element=' + i;
	var script = document.createElement('script');                
	script.setAttribute('src', website_base_url + 'valdiklis/index.php?' + src);                
	document.getElementsByTagName('head')[0].appendChild(script);								
}
			
//callback for JSONP requests - creation of the widgets
function create_lplus_widget(data) {								
	var widget = document.createElement('div');														
	var element = LplusElements.elements[data.element];
	widget.setAttribute('class', 'lplius-widget-container');
	widget.setAttribute('className', 'lplius-widget-container');
	widget.setAttribute('style', 'width:' + (data.width - 2) + 'px');
	widget.style['width'] = (data.width - 2) + 'px';
	widget.innerHTML = data.data;  				
	element.parentNode.replaceChild(widget, element);
				
}
	                                                
//helper function where native is not available     
function getElementsByClassName(node,classname) {
	if (node.getElementsByClassName) { // use native implementation if available
		return node.getElementsByClassName(classname);		
	} else {
		return (function getElementsByClass(searchClass,node) {
			if ( node == null )
				node = document;
			var classElements = [],
			els = node.getElementsByTagName("a"),
			elsLen = els.length,
			pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)"), i, j;

			for (i = 0, j = 0; i < elsLen; i++) {
				if ( pattern.test(els[i].className) ) {
					classElements[j] = els[i];
					j++;
				}
			}
			return classElements;
		})(classname, node);
	}
}                                                     