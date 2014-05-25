<div class="box">
    <div class="box-header">
        <span class="title" data-bind="text: modelTitle"></span>

        <ul class="box-toolbar">
            <li data-bind="visible: loadingRows">
                <span class="label label-green"><?php echo trans('admin::admin.loading') ?></span>
            </li>
            <li data-bind="visible: pagination.last() === 0">
                <span><?php echo trans('admin::admin.noresults') ?></span>
            </li>
            <li class="toolbar-link">
                <!-- ko if: globalActions().length -->
                <!-- ko foreach: globalActions -->
                <!-- ko if: has_permission -->
                <input type="button" data-bind="click: function(){$root.customAction(false, action_name, messages, confirmation)}, value: title,
																		attr: {disabled: $root.freezeForm() || $root.freezeActions()}"/>
                <!-- /ko -->
                <!-- /ko -->
                <!-- /ko -->

                <!-- ko if: actionPermissions.create -->
                <a class="new_item"
                   data-bind="attr: {href: base_url + modelName() + '/new'},
								text: '<?php echo trans('admin::admin.new') ?> ' + modelSingle()">
                   <i class="fa fa-refresh"></i>
                </a>
                <!-- /ko -->
            </li>
        </ul>
    </div>

    <div class="box-content">
        <div class="table-responsive">
            <table class="table table-normal table-hover" id="customers">
                <thead>
                <tr>
                    <!-- ko foreach: columns -->
                    <th data-bind="visible: visible, css: {sortable: sortable,
                                    'sorted-asc': (column_name == $root.sortOptions.field() || sort_field == $root.sortOptions.field()) && $root.sortOptions.direction() === 'asc',
                                    'sorted-desc': (column_name == $root.sortOptions.field() || sort_field == $root.sortOptions.field()) && $root.sortOptions.direction() === 'desc'}">
                        <!-- ko if: sortable -->
                        <div
                            data-bind="click: function() {$root.setSortOptions(sort_field ? sort_field : column_name)}, text: title"></div>
                        <!-- /ko -->

                        <!-- ko ifnot: sortable -->
                        <div data-bind="text: title"></div>
                        <!-- /ko -->
                    </th>
                    <!-- /ko -->
                </tr>
                </thead>
                <tbody>
                <!-- ko foreach: rows -->
                <tr data-bind="click: function() {$root.clickItem($data[$root.primaryKey].raw); return true},
                                css: {result: true, even: $index() % 2 == 1, odd: $index() % 2 != 1,
                                selected: $data[$root.primaryKey].raw == $root.itemLoadingId()}">
                    <!-- ko foreach: $root.columns -->
                    <td data-bind="html: $parentContext.$data[column_name].rendered, visible: visible"></td>
                    <!-- /ko -->
                </tr>
                <!-- /ko -->
                </tbody>
            </table>
        </div>

        <div class="box-footer padded">
            <div class="row">
                    <div class="col-xs-2">
                        <input type="hidden" data-bind="value: rowsPerPage,
                                                select2: {
                                                    minimumResultsForSearch: -1,
                                                    data: {results: rowsPerPageOptions},
                                                    allowClear: false}"/>
                        <!--<label><?php echo trans('admin::admin.itemsperpage') ?></label>-->
                        <div class="action_message" data-bind="css: { error: globalStatusMessageType() == 'error', success: globalStatusMessageType() == 'success' },
                                        notification: globalStatusMessage "></div>
                    </div>
                    <div class="col-md-6 col-md-offset-4">
                        <div class="pull-right">
                            <input type="button" value="<?php echo trans('admin::admin.previous') ?>"
                                   data-bind="attr: {disabled: pagination.isFirst() || !pagination.last() || !initialized() }, click: function() {page('prev')}"
                                   class="btn btn-default"/>
                            <input type="button" value="<?php echo trans('admin::admin.next') ?>"
                                   data-bind="attr: {disabled: pagination.isLast() || !pagination.last() || !initialized() }, click: function() {page('next')}"
                                   class="btn btn-default"/>
                            <input type="text"
                                   data-bind="attr: {disabled: pagination.last() === 0 || !initialized() }, value: pagination.page"/>
                            <span data-bind="text: ' / ' + pagination.last()"></span>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div data-bind="itemTransition: activeItem() !== null || loadingItem()">
    <div class="item_edit" data-bind="template: 'itemFormTemplate'"></div>
</div>
