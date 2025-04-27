<ol class="sortable ui-sortable mjs-nestedSortable-branch mjs-nestedSortable-expanded">
    @foreach($menu->items as $item)
        <li style="display: list-item;" class="mjs-nestedSortable-leaf"
            id="menuItem_{{$item->id}}"
            data-foo="bar">
            <div class="menuDiv">
                <span title="Click to show/hide children"
                      class="disclose ui-icon ui-icon-minusthick"><span></span></span>
                <span title="Click to show/hide item editor" data-id="{{ $item->id }}"
                      class="expandEditor ui-icon ui-icon-triangle-1-s"><span></span></span><span>

			   <span data-id="{{ $item->id }}"
                     class="itemTitle">{{ $item->name }} <small>{{ $item->description }}</small></span>
                    @if(\App\Helpers\CommonHelper::hasPermission(['menu-action']))
                        <span title="Click to delete item." data-url="{{ route('menu.item.destroy', $item->id) }}"
                              data-id="{{ $item->id }}"
                              class="deleteMenu ui-icon ui-icon-closethick"><span></span></span>
                    @endif
                </span>
                <div id="menuEdit{{ $item->id }}" class="menuEdit" style="display: none">
                    <div class="menuSpace">
                        @if(\App\Helpers\CommonHelper::hasPermission(['menu-update']))
                            <form method="POST"
                                  action="{{ route('menu.item.update', $item->id) }}"
                                  class="menuUpdateForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="menu_id" value="{{ $item->menu_id }}">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name{{ $item->id }}">Name</label>
                                            <input type="text" id="name{{ $item->id }}" name="name"
                                                   value="{{ $item->name }}"
                                                   class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="menu_class{{ $item->id }}">Menu class</label>
                                            <input type="text" id="menu_class{{ $item->id }}" name="css_class"
                                                   value="{{ $item->css_class }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="menu_url{{ $item->id }}">URL</label>
                                            <input type="text" id="menu_url{{ $item->id }}" name="menu_url"
                                                   value="{{ $item->menu_url }}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="icon_class{{ $item->id }}">Menu Icon</label>
                                            <select name="icon_class"
                                                    class="form-control menuIcon">
                                                @if($item->icon_class)
                                                    <option
                                                        value="{{ $item->icon_class }}">{{ $item->icon_class }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description{{ $item->id }}">Description</label>
                                    <input type="text" id="description{{ $item->id }}" name="description"
                                           value="{{ $item->description }}"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <ol>
                @foreach($item->childs as $child)
                    <li style="display: list-item;"
                        class="mjs-nestedSortable-branch mjs-nestedSortable-expanded"
                        id="menuItem_{{ $child->id }}" data-foo="baz">
                        <div class="menuDiv">
				   <span title="Click to show/hide children" class="disclose ui-icon ui-icon-minusthick">
				   <span></span>
				   </span>
                            <span title="Click to show/hide item editor" data-id="{{ $child->id }}"
                                  class="expandEditor ui-icon ui-icon-triangle-1-s">
				   <span></span>
				   </span>
                            <span>

				   <span data-id="{{ $child->id }}"
                         class="itemTitle">{{ $child['name'] }} <small>{{ $child['description'] }}</small></span>
                                @if(\App\Helpers\CommonHelper::hasPermission(['menu-action']))
                                    <span title="Click to delete item." data-id="{{ $child->id }}"
                                          data-url="{{ route('menu.item.destroy', $child->id) }}"
                                          class="deleteMenu ui-icon ui-icon-closethick">
				   <span></span>
				   </span>
                                @endif
				   </span>
                            <div id="menuEdit{{ $child->id }}" class="menuEdit" style="display:none">
                                @if(\App\Helpers\CommonHelper::hasPermission(['menu-update']))
                                    <form method="POST" action="{{ route('menu.item.update', $child->id) }}"
                                          class="menuUpdateForm">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="menu_id" value="{{ $child->menu_id }}">
                                        <div class="form-group">
                                            <label for="name{{ $child->id }}">Name</label>
                                            <input type="text" id="name{{ $child->id }}" name="name"
                                                   value="{{ $child->name }}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="description-child{{ $child->id }}">Description</label>
                                            <input type="text" id="description-child{{ $child->id }}" name="description"
                                                   value="{{ $child->description }}"
                                                   class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="menu_url{{ $child->id }}">URL</label>
                                            <input type="text" id="menu_url{{ $child->id }}" name="menu_url"
                                                   value="{{ $child->menu_url }}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="menu_class{{ $child->id }}">Menu class</label>
                                            <input type="text" id="menu_class{{ $child->id }}" name="css_class"
                                                   value="{{ $child->css_class }}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="icon_class{{ $child->id }}">Menu Icon</label>
                                            <select name="icon_class"
                                                    class="form-control menuIcon">
                                                @if($child->icon_class)
                                                    <option
                                                        value="{{ $child->icon_class }}">{{ $child->icon_class }}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <ol>
                            @foreach($child->childs as $ch)
                                <li class="mjs-nestedSortable-leaf" id="menuItem_{{ $ch->id }}">
                                    <div class="menuDiv">
					   <span title="Click to show/hide children" class="disclose ui-icon ui-icon-minusthick">
					   <span></span>
					   </span>
                                        <span title="Click to show/hide item editor" data-id="{{ $ch->id }}"
                                              class="expandEditor ui-icon ui-icon-triangle-1-s">
					   <span></span>
					   </span>
                                        <span>

					   <span data-id="{{ $ch->id }}"
                             class="itemTitle">{{ $ch['name'] }} <small>{{ $ch['description'] }}</small></span>
					   <span title="Click to delete item." data-id="{{ $ch->id }}"
                             data-url="{{ route('menu.item.destroy', $ch->id) }}"
                             class="deleteMenu ui-icon ui-icon-closethick">
					   <span></span>
					   </span>
					   </span>
                                        <div id="menuEdit{{ $ch->id }}" class="menuEdit" style="display:none">
                                            @if(\App\Helpers\CommonHelper::hasPermission(['menu-update']))
                                                <form method="POST" action="{{ route('menu.item.update', $ch->id) }}"
                                                      class="menuUpdateForm">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="menu_id" value="{{ $ch->menu_id }}">
                                                    <div class="form-group">
                                                        <label for="name{{ $ch->id }}">Name</label>
                                                        <input type="text" id="name{{ $ch->id }}" name="name"
                                                               value="{{ $ch->name }}" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description-ch{{ $ch->id }}">Description</label>
                                                        <input type="text" id="description-ch{{ $ch->id }}"
                                                               name="description"
                                                               value="{{ $ch->description }}" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="menu_url{{ $ch->id }}">URL</label>
                                                        <input type="text" id="menu_url{{ $ch->id }}" name="menu_url"
                                                               value="{{ $ch->menu_url }}" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="menu_class{{ $ch->id }}">Menu class</label>
                                                        <input type="text" id="menu_class{{ $ch->id }}"
                                                               name="menu_class"
                                                               value="{{ $ch->menu_class }}" class="form-control">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="icon_class{{ $ch->id }}">Menu Icon</label>
                                                        <select name="icon_class"
                                                                class="form-control menuIcon">
                                                            @if($ch->icon_class)
                                                                <option
                                                                    value="{{ $ch->icon_class }}">{{ $ch->icon_class }}</option>
                                                            @endif
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </li>
                @endforeach
            </ol>
        </li>
    @endforeach
</ol>
<p><br>

<p>
    <button id="toArray" class="btn btn-primary pull-right" name="toArray"
            type="submit">{{ __('Save changes') }}</button>
</p>
<p id="toArrayOutput"></p>
