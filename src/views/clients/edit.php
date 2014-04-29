<div class="alert alert-info loading" data-bind="visible: loadingItem"><?php echo trans('admin::admin.loading') ?></div>

<form class="fill-up admin-edit" data-bind="visible: !loadingItem(), submit: saveItem">
<div class="box">
    <div class="box-header">
        <span class="title" data-bind="text: $root[$root.primaryKey]() ? '<?php echo trans('admin::admin.edit') ?>' : '<?php echo trans('admin::admin.createnew') ?>'"></span>
    </div>

    <div class="box-content padded">
        <!-- ko if: $root[$root.primaryKey]() -->
        <!-- ko if: $root.itemLink() -->
        <a class="item-link" target="_blank" data-bind="attr: {href: $root.itemLink()},
                                    text: '<?php echo trans('admin::admin.viewitem', array('single' => $config->getOption('single'))) ?>'"></a>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko foreach: editFields -->
        <!-- ko if: $data && ( $root[$root.primaryKey]() || editable ) && visible -->
        <div data-bind="attr: {class: type}">
        <div class="form-group">
        <label data-bind="attr: {for: field_id}, text: title + ':'"></label>

        <!-- ko if: type === 'key' -->
        <span data-bind="text: $root[$root.primaryKey]"></span>
        <!-- /ko -->

        <!-- ko if: type === 'text' -->
        <div class="characters_left" data-bind="charactersLeft: {value: $root[field_name], limit: limit}"></div>
        <!-- ko if: editable -->
        <input type="text" data-bind="attr: {disabled: $root.freezeForm, id: field_id}, value: $root[field_name],
                                                                                    valueUpdate: 'afterkeydown', characterLimit: limit"/>
        <!-- /ko -->
        <!-- ko ifnot: editable -->
        <div class="uneditable" data-bind="text: $root[field_name]()"></div>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'textarea' -->
        <div class="characters_left" data-bind="charactersLeft: {value: $root[field_name], limit: limit}"></div>
        <!-- ko if: editable -->
        <textarea data-bind="attr: {disabled: $root.freezeForm || !editable, id: field_id}, value: $root[field_name],
                                                                                valueUpdate: 'afterkeydown', characterLimit: limit,
                                                                                style: {height: height + 'px'}"></textarea>
        <!-- /ko -->
        <!-- ko ifnot: editable -->
        <div class="uneditable" data-bind="text: $root[field_name]"></div>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'wysiwyg' -->
        <!-- ko if: editable -->
        <textarea class="wysiwyg" data-bind="attr: {disabled: $root.freezeForm, id: field_id},
                                                    wysiwyg: {value: $root[field_name], id: field_id}"></textarea>
        <!-- /ko -->
        <!-- ko ifnot: editable -->
        <div class="uneditable" data-bind="html: $root[field_name]"></div>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'markdown' -->
        <!-- ko if: editable -->
        <div class="markdown_container" data-bind="style: {height: height + 'px'}">
            <div class="characters_left" data-bind="charactersLeft: {value: $root[field_name], limit: limit}"></div>
            <textarea data-bind="attr: {disabled: $root.freezeForm, id: field_id}, characterLimit: limit,
                                                                                value: $root[field_name], valueUpdate: 'afterkeydown'"></textarea>

            <div class="preview" data-bind="markdown: $root[field_name]"></div>
        </div>
        <!-- /ko -->
        <!-- ko ifnot: editable -->
        <div class="uneditable" data-bind="markdown: $root[field_name]"></div>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'password' -->
        <div class="characters_left" data-bind="charactersLeft: {value: $root[field_name], limit: limit}"></div>
        <input type="password" data-bind="attr: {disabled: $root.freezeForm, id: field_id}, value: $root[field_name],
                                                                                valueUpdate: 'afterkeydown', characterLimit: limit"/>
        <!-- /ko -->

        <!-- ko if: type === 'belongs_to' -->
        <!-- ko if: loadingOptions -->
        <div class="loader"></div>
        <!-- /ko -->

        <!-- ko if: autocomplete -->
        <input type="hidden" data-bind="attr: {disabled: $root.freezeForm() || loadingOptions() || constraintLoading() || !editable,
                                                                id: field_id},
                                                    value: $root[field_name], select2Remote: {field: field_name, type: 'edit', constraints: constraints}"/>
        <!-- /ko -->

        <!-- ko ifnot: autocomplete -->
        <input type="hidden" data-bind="attr: {disabled: $root.freezeForm() || loadingOptions() || constraintLoading() || !editable,
                                                                id: field_id},
                                                    value: $root[field_name], select2: {data:{results: $root.listOptions[field_name]}}"/>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'belongs_to_many' -->
        <!-- ko if: loadingOptions -->
        <div class="loader"></div>
        <!-- /ko -->

        <!-- ko if: autocomplete -->
        <input type="hidden" data-bind="attr: {disabled: $root.freezeForm() || loadingOptions() || constraintLoading() || !editable,
                                                                id: field_id},
                                            select2Remote: {field: field_name, type: 'edit', multiple: true, constraints: constraints, sort: sort_field},
                                            value: $root[field_name]"/>
        <!-- /ko -->

        <!-- ko ifnot: autocomplete -->
        <input type="hidden" data-bind="attr: {disabled: $root.freezeForm() || loadingOptions() || constraintLoading() || !editable,
                                                                id: field_id},
                                                            select2: {data:{results: $root.listOptions[field_name]}, multiple: true, sort: sort_field},
                                                            value: $root[field_name]"/>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'number' -->
        <!-- ko if: editable -->
        <span class="symbol" data-bind="text: symbol"></span>
        <input type="text" data-bind="attr: {disabled: $root.freezeForm, id: field_id}, value: $root[field_name],
                                                        number: {decimals: decimals, key: field_name, thousandsSeparator: thousands_separator,
                                                                decimalSeparator: decimal_separator}"/>
        <!-- /ko -->
        <!-- ko ifnot: editable -->
        <span data-bind="text: symbol"></span>
                            <span class="uneditable" data-bind="value: $root[field_name], number: {decimals: decimals, key: field_name,
                                                                                            thousandsSeparator: thousands_separator,
                                                                                            decimalSeparator: decimal_separator}"></span>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'bool' -->
        <!-- ko if: editable -->
        <input type="checkbox"
               data-bind="attr:{disabled: $root.freezeForm, id: field_id}, bool: field_name, checked: $root[field_name]"/>
        <!-- /ko -->
        <!-- ko ifnot: editable -->
        <span data-bind="text: $root[field_name]() ? 'yes' : 'no'"></span>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'enum' -->
        <input type="hidden" data-bind="attr: {disabled: $root.freezeForm, id: field_id}, value: $root[field_name],
                                                        select2: {data: {results: options}}"></select>
        <!-- /ko -->

        <!-- ko if: type === 'date' -->
        <!-- ko if: editable -->
        <input type="text" data-bind="attr: {disabled: $root.freezeForm, id: field_id}, value: $root[field_name],
                                                                                        datepicker: {dateFormat: date_format}"/>
        <!-- /ko -->
        <!-- ko ifnot: editable -->
        <div class="uneditable" data-bind="formatDate: {dateFormat: date_format, value: $root[field_name]()}"></div>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'time' -->
        <!-- ko if: editable -->
        <input type="text" data-bind="attr: {disabled: $root.freezeForm, id: field_id}, value: $root[field_name],
                                                                                    timepicker: {timeFormat: time_format}"/>
        <!-- /ko -->
        <!-- ko ifnot: editable -->
        <div class="uneditable" data-bind="formatTime: {timeFormat: time_format, value: $root[field_name]()}"></div>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'datetime' -->
        <!-- ko if: editable -->
        <input type="text" data-bind="attr: {disabled: $root.freezeForm, id: field_id}, value: $root[field_name],
                                                                                datetimepicker: {dateFormat: date_format, timeFormat: time_format}"/>
        <!-- /ko -->
        <!-- ko ifnot: editable -->
        <div class="uneditable" data-bind="formatDateTime: {timeFormat: time_format, dateFormat: date_format,
                                                                                value: $root[field_name]()}"></div>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'image' -->
        <div class="upload_container" data-bind="attr: {id: field_id}">
            <div class="uploader" data-bind="attr: {disabled: $root.freezeForm, id: field_name + '_uploader'}, value: $root.activeItem,
                                                    fileupload: {field: field_name, size_limit: size_limit, uploading: uploading, image: true,
                                                                upload_percentage: upload_percentage, upload_url: upload_url}">
                <?php echo trans('admin::admin.uploadimage') ?></div>
            <!-- ko if: uploading -->
            <div class="uploading"
                 data-bind="text: '<?php echo trans('admin::admin.imageuploading') ?>' + upload_percentage() + '%'"></div>
            <!-- /ko -->
        </div>

        <!-- ko if: $root[field_name]() && !$root.loadingItem() -->
        <div class="image_container">
            <img data-bind="attr: {src: file_url + '?path=' + location + $root[field_name]()}"
                 onload="window.admin.resizePage()"/>
            <input type="button" class="remove_button" data-bind="click: function() {$root[field_name](null)}" value="x"/>
        </div>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'file' -->
        <div class="upload_container" data-bind="attr: {id: field_id}">
            <div class="uploader" data-bind="attr: {disabled: $root.freezeForm, id: field_name + '_uploader'}, value: $root.activeItem,
                                                    fileupload: {field: field_name, size_limit: size_limit, uploading: uploading,
                                                                upload_percentage: upload_percentage, upload_url: upload_url}">
                <?php echo trans('admin::admin.uploadfile') ?></div>
            <!-- ko if: uploading -->
            <div class="uploading"
                 data-bind="text: '<?php echo trans('admin::admin.fileuploading') ?>' + upload_percentage() + '%'"></div>
            <!-- /ko -->
        </div>

        <!-- ko if: $root[field_name] -->
        <div class="file_container">
            <a data-bind="attr: {href: file_url + '?path=' + location + $root[field_name](), title: $root[field_name]},
                                    text: $root[field_name]"></a>
            <input type="button" class="remove_button" data-bind="click: function() {$root[field_name](null)}" value="x"/>
        </div>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: type === 'color' -->
        <input type="text" data-type="color"
               data-bind="attr: {disabled: $root.freezeForm, id: field_id}, value: $root[field_name]"/>

        <div class="color_preview" data-bind="style: {backgroundColor: $root[field_name]}, visible: $root[field_name]"></div>
        <!-- /ko -->
        </div>
        </div>
        <!-- /ko -->
        <!-- /ko -->

        <!-- ko if: $root[$root.primaryKey]() && actions().length -->
        <div class="custom_buttons">
            <!-- ko foreach: actions -->
            <!-- ko if: has_permission && $root.actionPermissions[action_name] !== false -->
            <input type="button" data-bind="click: function(){$root.customAction(true, action_name, messages, confirmation)}, value: title,
                                                                            attr: {disabled: $root.freezeForm() || $root.freezeActions()}"/>
            <!-- /ko -->
            <!-- /ko -->
        </div>
        <!-- /ko -->

        <div class="control_buttons">
            <!-- ko if: $root[$root.primaryKey]() -->
            <button class="btn btn-xs btn-default" data-bind="click: closeItem, attr: {disabled: $root.freezeForm() || $root.freezeActions()}"><?php echo trans('admin::admin.close') ?></button>

            <!-- ko if: actionPermissions.delete -->
            <button class="btn btn-xs btn-red" data-bind="click: deleteItem, attr: {disabled: $root.freezeForm() || $root.freezeActions()}"><?php echo trans('admin::admin.delete') ?></button>
            <!-- /ko -->

            <!-- ko if: actionPermissions.update -->
            <button class="btn btn-xs btn-green" type="submit" data-bind="attr: {disabled: $root.freezeForm() || $root.freezeActions()}"><?php echo trans('admin::admin.save') ?></button>
            <!-- /ko -->
            <!-- /ko -->

            <!-- ko ifnot: $root[$root.primaryKey]() -->
            <button class="btn btn-xs btn-default" type="button" data-bind="click: closeItem, attr: {disabled: $root.freezeForm() || $root.freezeActions()}"><?php echo trans('admin::admin.cancel') ?></button>
            <!-- ko if: actionPermissions.create -->
            <button class="btn btn-xs btn-green" type="submit" data-bind="attr: {disabled: $root.freezeForm() || $root.freezeActions()}"><?php echo trans('admin::admin.create') ?></button>
            <!-- /ko -->
            <!-- /ko -->
            <span class="message" data-bind="css: { error: statusMessageType() == 'error', success: statusMessageType() == 'success' }, notification: statusMessage "></span>
        </div>

    </div>
</div>

</form>