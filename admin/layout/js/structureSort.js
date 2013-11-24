$('#structure-tree li').prepend('<div class="dropzone"></div>');

    $('#structure-tree .handle, #structure-tree .dropzone').droppable({
        accept: '#structure-tree li',
        tolerance: 'pointer',
        drop: function(e, ui) {
            var li = $(this).parent();
            var child = !$(this).hasClass('dropzone');
            if (child && li.children('ul').length == 0) {
                li.append('<ul/>');
            }
            if (child) {
                li.addClass('sm2_liOpen').removeClass('sm2_liClosed').children('ul').append(ui.draggable);
            }
            else {
                li.before(ui.draggable);
            }
			$('#structure-tree li.sm2_liOpen').not(':has(li:not(.ui-draggable-dragging))').removeClass('sm2_liOpen');
            li.find('.handle,.dropzone').css({ backgroundColor: '', borderColor: '' });
			
			var object = $('#structure-tree');
					
			var returnArray = function()
			{
				var data,
					depth = 0,
					list = this;
					step = function(level, depth)
					{
						var array = [ ],
							items = level.children('li');
						items.each(function()
						{
							var li = $(this),
								item = $.extend({}, li.data()),
								sub = li.children('ul');
							if (sub.length) {
								item.children = step(sub, depth + 1);
							}
							array.push(item);
						});
						return array;
					};
				data = step(object, depth);
				return data;
			};
			
			var retunArray = returnArray();
			console.log(JSON.stringify(returnArray));
			
        },
        over: function() {
            $(this).filter('.handle, .dropzone').css({ backgroundColor: '#ccc' });
        },
        out: function() {
            $(this).filter('.handle, .dropzone').css({ backgroundColor: '' });
        }
    });
	
    $('#structure-tree li').draggable({
        handle: ' > .handle',
        opacity: .8,
        addClasses: false,
        helper: 'clone',
        zIndex: 100,
    });