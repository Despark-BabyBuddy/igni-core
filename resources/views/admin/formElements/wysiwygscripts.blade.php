@php
    $videos = \Despark\Model\Video::all();
    $questions = \Despark\Model\KnowledgeBase::all();
    $words = \Despark\Model\Word::all();
@endphp

<script type="text/javascript">
    var videosList = [
        @foreach ($videos as $videoItem)
            { id: {{ $videoItem->id }}, text: '{{ $videoItem->title }}' },
        @endforeach
    ];

    var questionsList = [
        @foreach ($questions as $questionItem)
            { id: {{ $questionItem->id }}, text: '{{ $questionItem->question }}', data_thumb: 'ds' },
        @endforeach
    ];

    var wordsList = [
        @foreach ($words as $wordItem)
            { id: {{ $wordItem->id }}, text: '{{ $wordItem->name }}' },
        @endforeach
    ];

    function select2Initialize(inputID, data) {
        $('#' + inputID).select2({
            containerCssClass: 'mce-selectbox mce-abs-layout-item mce-last managed-window-select2',
            dropdownCssClass: 'select2-dropdown-data-container',
            data: data
        })
        .on('select2:open', function (event) {
            var $select = $('span.managed-window-select2');
            var $options = $('.select2-dropdown-data-container');
            $options.css('left', $select.css('left'));
            $options.css('top', parseInt($select.css('top')) + 28);
        });
    }

    // From webapp to keep consistency
    function convertToSlug(Text) {
    	return Text.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
    }

    function select2Slug(inputID, data, includeIdSuffix = true) {
        $('#' + inputID).select2({
            containerCssClass: 'mce-selectbox mce-abs-layout-item mce-last managed-window-select2',
            dropdownCssClass: 'select2-dropdown-data-container',
            data: data,
            templateSelection: function (data, container) {
                var slug = convertToSlug(data.text) + (includeIdSuffix ? '-' + data.id : '');
                $(data.element).attr('data-slug', slug);
                return data.text;
            }
        })
        .on('select2:open', function (event) {
            var $select = $('span.managed-window-select2');
            var $options = $('.select2-dropdown-data-container');
            $options.css('left', $select.css('left'));
            $options.css('top', parseInt($select.css('top')) + 28);
        });
    }

    function merge_options(obj1, obj2) {
        var obj3 = {};
        for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
        for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
        return obj3;
    }

    var defaultOptions = {
        selector: ".wysiwyg",
        skin: "despark-cms",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen responsivefilemanager template",
            "insertdatetime media table contextmenu paste imagetools jbimages"
        ],

        menubar: false,
        toolbar: "code undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image jbimages | media | template | constants inapplink",
        image_advtab: true,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        height: 500,
        imagetools_cors_hosts: ['{{env('APP_URL')}}'],
        external_filemanager_path: "/plugins/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {
            "filemanager": "{{ asset('/plugins/filemanager/plugin.min.js') }}"
        },
        paste_as_text: true,
        media_live_embeds: true,
        style_formats: [
          {title: 'Headers', items: [
            {title: 'Header 1', format: 'h1'},
            {title: 'Header 2', format: 'h2'},
            {title: 'Header 3', format: 'h3'},
            {title: 'Header 4', format: 'h4'},
            {title: 'Header 5', format: 'h5'},
            {title: 'Header 6', format: 'h6'}
          ]},
          {title: 'Inline', items: [
            {title: 'Bold', icon: 'bold', format: 'bold'},
            {title: 'Italic', icon: 'italic', format: 'italic'},
            {title: 'Underline', icon: 'underline', format: 'underline'},
            {title: 'Strikethrough', icon: 'strikethrough', format: 'strikethrough'},
            {title: 'Superscript', icon: 'superscript', format: 'superscript'},
            {title: 'Subscript', icon: 'subscript', format: 'subscript'},
            {title: 'Code', icon: 'code', format: 'code'}
          ]},
          {title: 'Blocks', items: [
            {title: 'Paragraph', format: 'p'},
            {title: 'Blockquote', format: 'blockquote'},
            {title: 'Div', format: 'div'},
            {title: 'Pre', format: 'pre'}
          ]},
          {title: 'Alignment', items: [
            {title: 'Left', icon: 'alignleft', format: 'alignleft'},
            {title: 'Center', icon: 'aligncenter', format: 'aligncenter'},
            {title: 'Right', icon: 'alignright', format: 'alignright'},
            {title: 'Justify', icon: 'alignjustify', format: 'alignjustify'}
          ]},
          {
              title: 'Containers', items: [
              {
                  title: 'First div',
                  block: 'div',
                  classes: 'col-lg-4 col-lg-offset-2',
                  wrapper: true,
                  merge_siblings: false
              },
              {
                  title: 'Second div',
                  block: 'div',
                  classes: 'col-lg-4',
                  wrapper: true,
                  merge_siblings: false
              },
              ]
          }
        ],
        end_container_on_empty_block: true,

          setup: function(editor) {

            function toTimeHtml(date) {
              return '<time datetime="' + date.toString() + '">' + date.toDateString() + '</time>';
            }

            function insertDate() {
              var html = toTimeHtml(new Date());
              editor.insertContent(html);
            }

            function updateFields(win, field) {
                    win.find('.selector').hide();
                    $('.mce-window .select2').hide();
                    var s = field.value();//
                    s = s.replace("applink://", "");
                    s = s.replace("?", "");
                    s = s.replace("=", "");

                    var newInput = win.find('#' + s );
                    newInput.show();

                    if (newInput.hasClass('searchSelect')) {
                        var inputID = newInput[0]._id;;
                        $('#' + inputID).next().show();
                    }
             }

            editor.addButton('constants', {
              image: 'https://image.flaticon.com/icons/png/128/64/64313.png',
              tooltip: "Insert Constant",
              type: 'menubutton',
               menu: [
                <?php

                    $consts = \Despark\Helpers\BabyBuddy::config("application.constants");
                    foreach($consts as $value => $key) {
                        echo '{
                            text: " ' . $key . '",
                            onclick: function() {
                                editor.insertContent("' . $value . '");
                            }
                        },';

                    }
                ?>

                ],
            });
            editor.addButton('inapplink', {
              icon: 'tasks',
              image: 'https://image.flaticon.com/icons/png/128/73/73455.png',
              tooltip: "Insert InApp link",
              type: 'button',
              stateSelector:"a[href]",
              onclick: function (e) {
                var node = editor.selection.getNode();
                var href = node.href;
                var id = null;
                if(href) {
                    var equalsign = href.lastIndexOf("=");
                    if(equalsign != -1) {
                        var url = href.substring(0, equalsign+1);
                        id = href.replace(url, "");
                    } else
                        url = href;
                }
                var win = editor.windowManager.open( {
                    title: 'Insert InApp link',
                    body: [{
                        type: 'textbox',
                        name: 'title',
                        value: editor.selection.getContent(),
                        multiline: true,
                        minWidth: 700,
                        minHeight: 50,
                    },{
                        type: 'listbox',
                        name: 'section',
                        classes: "mainselector",
                        values : [
                        <?php

                                $sections = \Despark\Helpers\BabyBuddy::config("application.sections");
                                foreach($sections as $value => $key)
                                    echo '{ text: "' . $key .'", value: "' . $value . '" },';
                        ?>
                        ],
                        value: url,
                        onselect: function(e) {
                                updateFields(win, this);
                        },
                    },{
                        type: 'selectbox',
                        name: 'videosvideo',
                        id: 'searchVideos',
                        classes: "selector searchSelect",
                        hidden: url == 'applink://videos?video=' ? false : true,
                        value: id,
                        onPostRender: function (event) {
                            select2Slug(this._id, videosList, true);
                            $('#' + this._id).next().hide();
                        }
                    },{
                        type: 'checkbox',
                        text: 'Insert thumbnail',
                        name: 'videosvideo',
                        classes: "insert_thumb selector",
                        hidden: url == 'applink://videos?video=' ? false : true,
                        values : [],
                        value: id
                    },{
                        type: 'selectbox',
                        name: 'ask_mequestion',
                        id: 'searchQuestions',
                        classes: "selector searchSelect",
                        hidden: url == 'applink://ask_me?question=' ? false : true,
                        value: id,
                        onPostRender: function (event) {
                            select2Slug(this._id, questionsList, true);
                            $('#' + this._id).next().hide();
                        }
                    },{
                        type: 'selectbox',
                        name: 'what_does_that_meanword',
                        id: 'searchWords',
                        classes: "selector searchSelect",
                        hidden: url == 'applink://what_does_that_mean?word=' ? false : true,
                        value: id,
                        onPostRender: function (event) {
                            select2Slug(this._id, wordsList, false);
                            $('#' + this._id).next().hide();
                        }
                    },
                    ],
                    onInit: function() {
                    },
                    onsubmit: function( e ) {
                        var weblinks = {<?php

                            $weblinks = \Despark\Helpers\BabyBuddy::config("application.weblinks");
                            foreach($weblinks as $k =>$v)
                                    echo "'$k': '$v',";

                        ?>};

                        var thumbnails = {<?php

                            $thumbs = \Despark\Model\Video::all();
                            foreach($thumbs as $v)
                                    echo "'{$v->id}': '" . addslashes($v->avatar_url()) . "',";

                        ?>};
                        var subinput = win.find(".selector:visible");
                        var insert_thumb = win.find(".insert_thumb:visible").value();
                        var html_content = e.data.title;

                        var subitem, subInputText;

                        if (subinput.hasClass('searchSelect')) {
                            var selectID = subinput[0]._id;
                            var $input = $('#' + selectID + ' option:selected');
                            subitem = $input.val();

                            if ($input.data('slug')) {
                                subInputText = $input.data('slug');
                            } else {
                                subInputText = $input.text();
                            }
                        } else {
                            subitem = subinput.value();
                            subInputText = subinput.text();
                        }

                        var link = e.data.section;


                        if (weblinks[link]) {
                            var weblink = weblinks[link];
                        }

                        if (subInputText) {
                            weblink = weblink + subInputText;
                        }

                        if (insert_thumb) {
                            html_content = '<img  class="video-thumb" style="width: 300px!important" src="' + thumbnails[subitem] + '">';
                        }

                        if (subitem) {
                            link = link + subitem;
                        }

                        if(link == 'applink://remember_to_ask?action=add&question=') {
                            link = link + e.data.title;
                        }

                        var output = '<a href="' +  link + '"  class="in-app-link"';

                        if (weblink) {
                            output = output + 'data-weblink="' + weblink + '"';
                        }

                        output = output + "'>" + html_content + '</a>'

                        tinyMCE.activeEditor.selection.setContent(output);
                    }
                });
             }

        });


        }
    };

    var additionalOptions = {!! json_encode($field->getOptions('additional_options')) !!};
    var desparkOptions = {};

    $(".mce-mainselector").change(function() {
        alert(this.val());
    });

    tinymce.init(merge_options(defaultOptions, desparkOptions));

</script>
<style>
    select.mce-searchSelect {
        height: 30px !important;
    }

    span.managed-window-select2 {
        width: 700px;
        height: 15px;
        padding: 4px 8px !important;
        left: 15px;
        top: 115px;
    }

    span.managed-window-select2 span.select2-selection__rendered {
        padding: 0px !important;
    }

    span.select2-dropdown-data-container {
        z-index: 99999;
    }
</style>
