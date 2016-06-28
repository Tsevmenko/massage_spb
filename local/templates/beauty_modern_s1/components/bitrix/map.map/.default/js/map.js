//'use strict';

(function (requires, versionOptions) {
	var $,
		$Common,
		$Map,
		$Params,
		$Balloon,
		$Temp,

		isOverlayPrepareMap,
		theSameAddress,
		balloonIsOpen,
		noScrollDelta,
		loading = {},
		clusterDone = true,

		_class = {
			update: function (data) {
				var defer = $.Deferred();

				if (data) {
					$Temp.parseData(data, function (queryParams) {
						createData(queryParams, function (info) {
							if ($Temp.slideMode) {
								if ($Temp.overlayMode) {
									if (!$Temp.getActiveItem()) {
										$Map.fitMap($Temp.getMapBounds());
									}
								} else {
									$Map.fitMap($Temp.getMapBounds());
									isOverlayPrepareMap = false;
								}
							} else {
								$Map.fitMap($Temp.getMapBounds());
							}

							data = null;
							defer.resolve.call(_class, info);
						});
					});
				} else {
					var info = _class.getMapObjects();

					showMapObjectsInfo(info);
					defer.resolve.call(_class, info);
				}

				return defer.promise();
			},

			addItemClasses: function (data, values) {
				
			},

			createItemContent: function (options) {
				if ($Temp.slideMode && _class.get('itemCustomView')) {
					var data = Object.keys(options.data),
						index = data.indexOf(options.correspondence.url),
						url,
						result = {
							content: [options.route]
						};

					if (index >= 0) {
						url = options.data[data.splice(index, 1)[0]];
					}

					for (var i = 0; i < data.length; i++) {
						var name = data[i];

						switch (name) {
							case 'cat':
								break;
							default:
								var field = options.fields[name],
									value = options.data[name];

								if (field) {
									if (!field.hidden) {
										switch (field.name) {
											case 'photo':
												result.photo = value;
												break;
											case 'name':
												if (url) {
													value = $Common.createElement('a', {
														href: url
													}).text(value)
												}

												result.content.unshift(create(null, value, 'bxmap-popup-item-name'));
												break;
											case 'phone':
												result.content.push(create(
													field.title,
													$Temp.createPhones(value)
												));
												break;
											case 'link':
											case 'url':
												result.content.push(create(
													field.title,
													$Temp.createLinks(value)
												));
												break;
											default:
												result.content.push(create(field.title, value));
												break;
										}
									}
								} else {
									result.content.push(create(null, value));
								}
						}
					}

					return result;
				} else {
					return {
						photo: $Temp.getValue(options.data, 'photo', options.catID),
						content: options.content
					};
				}

				function create (name, value, classes) {
					var block = $Common.createElement('*dl', 'bxmap-item-custom').append($Common.createElement('*dd', 'bxmap-item-custom-description' + (classes ? ' ' + classes : '')).append(value));

					if (name) {
						block.prepend($Common.createElement('*dt', 'bxmap-item-custom-name').text(name));
					}

					return block;
				}
			},

			toggleOverlayMode: function (type) {
				var method;

				if (type) {
					switch (type) {
						case 'hide':
						case 'close':
							method = 'removeClass';
							$Temp.overlayMode = false;
							break;
						default:
							method = 'addClass';
							$Temp.overlayMode = true;
							break;
					}
				} else {
					switch (type) {
						case undefined:
							$Temp.overlayMode = !$Temp.overlayMode;
							method = $Temp.overlayMode ? 'addClass' : 'removeClass';
							break;
						default:
							method = 'removeClass';
							$Temp.overlayMode = false;
							break;
					}
				}

				$(document.documentElement)[method]('bxmap-root-overflow');
				this.getWrapper()[method]('bxmap-overlay');
				$Common.replaceState({
					query: {
						type: $Temp.overlayMode ? 'geo' : null
					}
				});

				if (!_class.getWrapper().hasClass('bxmap-error')) {
					if ($Temp.slideMode) {
						if ($Temp.overlayMode) {
							$Temp.resize();

							if (!isOverlayPrepareMap) {
								isOverlayPrepareMap = true;
								$Map.refreshMap(true);
							}

							if (!clusterDone) {
								switch (_class.get('pageType')) {
									case 'objects':
									case 'events':
										var queryItem = $Common.getQuery({
											name: 'item'
										});

										if (queryItem) {
											var id = queryItem[0],
												item = $Temp.getItems(id);

											if (!$Temp.getActiveID(id)) {
												resetActiveMarker(true);
											}

											_class.getWrapper().one('cluster:done', function (e) {
												setActiveMarker(
													id,
													item.cat,
													item,
													true
												);
											});
											$Map.updateMarkerCluster($Temp.getVisibleMarkers());
										}

										break;
								}
							}
						}
					} else {
						$Temp.resize();
						$Map.refreshMap(!isOverlayPrepareMap);

						if (!isOverlayPrepareMap) {
							isOverlayPrepareMap = true;
						}
					}
				}

				return this.getMode();
			},

			showMapObjects: function (options) {
				var defer = $.Deferred(),
					data;

				if (options) {
					data = {
						query: $Common.getQuery({
							query: {
								cat: options.cat,
								item: options.item,
								type: options.type
							}
						}),
						title: options.title,
						filter: options.filter
					};
					setCats();
				} else {
					data = {
						query: $Common.getQuery({
							query: {
								cat: $Temp.getSingleCats()
							}
						})
					};
				}

				$Temp.parseData(data, function (queryParams) {
					$Temp.checkCounts(queryParams, function (info) {
						defer.resolve.call(_class, info);
					});
				});

				return defer.promise();

				function setCats () {
					if (data.query.cat) {
						var cats = {};

						set(data.query.cat);
						data.query.cat = Object.keys(cats);
					}

					function set (list) {
						list.forEach(function (id) {
							if ($Temp.getSingleCats(id)) {
								cats[id] = true;
							} else {
								set($Temp.getCats(id).childrenCats);
							}
						});
					}
				}
			},

			hideMapObjects: function (options) {
				var cats,
					items;

				if (options) {
					cats = $Common.getArray(options.cat);
					items = $Common.getArray(options.item);

					if (options.item && options.item.indexOf($Temp.getActiveID()) >= 0) {
						$Temp.toggleActiveItem(options.item);
					}

					if (cats.length) {
						for (var i = 0; i < cats.length; i++) {
							if ($Temp.getActiveGroupCats(cats[i])) {
								hideGroupCats(cats[i]);
							}
						}
					}

					if (options.type) {
						$Temp.toggleOverlayMode(options.type);
					}
				}

				return hideActiveObjects(cats);
			},

			filterMapObjects: function (options) {
				var oldItems = $Temp.getFilter('items', 'visible'),
					data = {
						mode: 'add'
					},
					info;

				if (options) {
					switch ($Common.getType(options)) {
						case 'String':
							data.query = options;
							break;
						case 'Object':
							data = options;
							break;
					}
				} else {
					data.mode = 'remove';
				}

				$Temp.filterObjects(data);
				manageView({
					items: oldItems
				});

				info = this.getMapObjects();
				showMapObjectsInfo(info);
				return this.getMapObjects();
			},

			getMode: function (name) {
				var list = [
						'overlayMode',
						'noPanelsMode',
						'noCatsMode',
						'catsMode',
						'subCatsMode',
						'directionMode'
					];

				if (name) {
					if (list.indexOf(name) >= 0) {
						return !!$Temp[name];
					}
				} else {
					for (var i = list.length, params = {}; i--;) {
						name = list[i];

						if ($Temp[name]) {
							params[name] = $Temp[name];
						}
					}

					return params;
				}
			},

			setNoPanelsMode: function (status) {
				$Temp.setNoPanelsMode(status);
				return this.getMode();
			},

			setNoCatsMode: function (status) {
				$Temp.setNoCatsMode(status);
				return this.getMode();
			},

			setCatsMode: function () {
				$Temp.setCatsMode();
				return this.getMode();
			},

			setSubCatsMode: function () {
				$Temp.setSubCatsMode();
				return this.getMode();
			},

			setDirectionMode: function (value) {
				switch ($Common.getType(value)) {
					case 'String':
						if ($Temp.getActiveID(value)) {
							show();
						} else {
							this.showMapObjects({
								item: value
							}).then(function () {
								if ($Temp.getActiveID(value)) {
									show();
								}
							});
						}

						break;
					case 'Boolean':
						if (value) {
							show();
						} else {
							close();
						}

						break;
					default:
						if ($Temp.directionMode) {
							close();
						} else {
							show();
						}

						break;
				}

				return this.getMode();

				function show () {
					if ($Temp.getActiveID()) {
						$Temp.setPanelsView({
							direction: 'show'
						});
						$Temp.getLayer('direction').trigger('set:direction');
					}
				}

				function close () {
					$Temp.setPanelsView({
						direction: 'collapse'
					});
				}
			},

			createDirection: function (options) {
				
			},

			setTitle: function (value) {
				if ($Temp.wrapperTitle) {
					$Temp.wrapperTitle.text(value || this.get('title') || document.title);
				}
			}
		};

	$GeoMapp.install(
		_class,
		registrate,
		start
	);

	function registrate (initOptions) {
		$ = this.$;
		_class = this;
		$Common = this.get('$Common');
		$Map = this.get('$Map');
		$Params = this.get('$Params');
		$Temp = $.extend(this.get('$Temp'), {
			container: $Common.createElement('*div', 'bxmap-container'),
			mapContainer: $Common.createElement('*div', 'bxmap-canvas'),
			wrapperTitle: $Common.createElement('*div', 'bxmap-overlay-title'),
			clearBlock: $Common.createElement('*div', 'bxmap-list-clear', {
				'data-container': 'clear'
			}).append(
				$Common.createElement('button', 'bxmap-clear-button btn btn-blue', {
					type: 'button',
					'data-action': 'clear'
				})
				.text((initOptions.interfaceText || _class.get('interfaceText')).clearCategories)
			),
			buttonHeight: 0,
			catStep: 0,
			includes: {},
			active: {},
			layers: {},
			filters: {},
			scrolls: {},
			create: {
				before: function (data) {
					switch (data.mode) {
						case 'replace':
							$Temp.reset();
						case 'reselect':
							$Temp.clear();
							break;
					}

					if (data.title) {
						_class.setTitle(data.title);
					}
				},
				item: createItem,
				cat: createCatItem
			},

			checkCounts: function (query, callback) {
				if (query.cat) {
					var groupList = [],
						singleList = [];

					for (var i = query.cat.length; i--;) {
						var id = query.cat[i],
							cat = $Temp.getCats(id);

						if ($Temp.getCurrentCats(id)) {
							if (cat.complete) {
								cat.element.removeClass('bxmap-uncomplete');
								delete cat.empty;
							}

							$Temp.setFilterCat(id);
						} else {
							singleList.push(id);
							groupList = groupList.concat(cat.chain.slice(1).reverse());
						}
					}

					createCatList(groupList, singleList);
				}

				if (query.type && !$Temp.overlayMode) {
					$Temp.overlayMode = true;
					_class.toggleOverlayMode(query.type);
				}

				showActiveObjects(query, null, callback);
			},

			reset: function (status) {
				_class.getWrapper().removeClass('bxmap-show-cats bxmap-show-subcats');
				hideActiveObjects();
				this.setCurrentCats();
				this.setGroupCats();
				this.setParentCats();
				this.setSingleCats();
				this.setCatSets();
				this.setItemSets();
				this.setCurrentItems();
				this.setMapBounds();
				this.getList('cats').empty();
				this.getList('items').empty();

				if (this.slideMode) {
					this.getList('subcats').empty().removeClass('bxmap-full');
				}
			},

			clear: function (status) {
				this.resetActiveCats();
				this.resetActiveGroupCats();
				this.setActiveItem();
				this.setActiveID();
				this.getContainer().removeClass('bxmap-toggle-subcats bxmap-toggle-objects bxmap-slide-collapse');
			},

			setNoPanelsMode: function (status) {
				this.noPanelsMode = !!status;

				if (status) {
					_class.getWrapper().addClass('bxmap-nopanels');
				} else {
					_class.getWrapper().removeClass('bxmap-nopanels');
				}
			},

			setNoCatsMode: function (status) {
				this.noCatsMode = !!status;

				if (status) {
					_class.getWrapper()
						.addClass('bxmap-nocats')
						.removeClass('bxmap-show-cats bxmap-show-subcats');
				} else {
					if (this.catsMode || this.subCatsMode) {
						if (this.catsMode) {
							this.setCatsMode();
						} else if (this.subCatsMode) {
							this.setSubCatsMode();
						}

						_class.getWrapper().removeClass('bxmap-nocats');
					}
				}
			},

			setCatsMode: function () {
				if (this.noCatsMode) {
					delete this.noCatsMode;
					_class.getWrapper().removeClass('bxmap-nocats');

					if (this.slideMode) {
						this.getContainer().addClass('bxmap-slide-collapse');
					}
				}

				this.catsMode = true;
				_class.getWrapper().addClass('bxmap-show-cats');

				if (this.slideMode) {
					if (this.getActiveGroupCats().length) {
						this.getContainer().addClass('bxmap-toggle-subcats');

						if (this.getActiveCats().length) {
							this.getContainer().addClass('bxmap-toggle-objects');
						} else {
							this.getContainer().removeClass('bxmap-toggle-objects');
						}
					} else {
						this.getContainer().removeClass('bxmap-toggle-subcats');
					}

					delete this.subCatsMode;
					this.getLayer('subcats').removeClass('bxmap-full');
					_class.getWrapper().removeClass('bxmap-show-subcats');
				}
			},

			setSubCatsMode: function () {
				if (this.slideMode) {
					if (this.noCatsMode) {
						delete this.noCatsMode;
						_class.getWrapper().removeClass('bxmap-nocats');
						this.getContainer().addClass('bxmap-slide-collapse');
					}

					if (this.catsMode) {
						delete this.catsMode;
						_class.getWrapper().removeClass('bxmap-show-cats');
					}

					if (this.getActiveCats().length) {
						this.getContainer().addClass('bxmap-toggle-objects');
					} else {
						this.getContainer().removeClass('bxmap-toggle-objects');
					}

					this.subCatsMode = true;
					this.getLayer('subcats').addClass('bxmap-full');
					_class.getWrapper().addClass('bxmap-show-subcats');
				} else {
					this.setCatsMode();
				}
			},

			getContainer: function () {
				return this.container;
			},

			setStep: function () {
				if (!this.catStep) {
					var cats = this.lists.cats.children();

					if (cats.length > 1) {
						this.catStep = Math.abs(cats.eq(0).position().top - cats.eq(1).position().top);
					}
				}
			},

			createList: function (name) {
				var tag = name == 'cats' ? 'ul' : 'div';

				this.lists[name] = $Common.createElement('*' + tag, 'bxmap-cat-list', {
					'data-category': 'container'
				});
				return this.lists[name];
			},

			createItemSet: function (id, options) {
				return $Common.createElement('*ul', 'bxmap-set-list ' + id, {
					'data-id': id
				});
			},

			createCatSet: function (id, options) {
				return $Common.createElement('*ul', 'bxmap-set-list ' + id, {
					'data-id': id,
					'data-set': 'set'
				});
			},

			createScroll: function (options) {
				options = options || {};

				var classes = options.classes ? ' ' + options.classes : '',
					params = {
						'data-wrapper': 'scroll'
					};

				if (options.params) {
					if (options.params['data-wrapper']) {
						params['data-wrapper'] += ' ' + options.params['data-wrapper'];
						delete options.params['data-wrapper'];
					}

					$.extend(params, options.params);
				}

				var _wrapper = $Common.createElement('*div', 'bxmap-scroll-wrapper' + classes, params),
					_container = $Common.createElement('*div', 'bxmap-scroll-container', {
						'data-container': 'scroll'
					}).appendTo(_wrapper),
					_rule = $Common.createElement('*div', 'bxmap-rule', {
						'data-container': 'rule'
					}).appendTo(_wrapper),
					_float = $Common.createElement('*div', 'bxmap-rule-float', {
						'data-action': 'scroll'
					}).appendTo(_rule),
					_content = $Common.createElement('*div', 'bxmap-scroll-content', {
						'data-content': 'scroll'
					}).appendTo(_container);

					_wrapper.data({
						container: _container,
						content: _content,
						rule: _rule,
						'float': _float
					});

				if (options.content) {
					_content.append(options.content);
				}

				this.setScrollReaction(_container);
				return _wrapper;
			},

			setScrollReaction: function (element) {
				if (_class.get('replaceRules')) {
					element.on('scroll', function (e) {
						e.stopPropagation();

						var _container = $(this),
							_wrapper = _container.closest('[data-wrapper~="scroll"]'),
							H = _container.height(),
							D = _wrapper.data('content').height(),
							h = _wrapper.data('rule').height(),
							d = Math.max(Math.round(h * H / D), _wrapper.data('floatHeight') || 0);

						_wrapper.data('float').css({
							top: Math.round((h - d) * _container.scrollTop() / (D - H)) + 'px'
						});
					});
				}
			},

			restoreScroll: function (id, item) {
				if (id && item) {
					this.setScroll(id, item).data({
						container: $('[data-container~="scroll"]', item),
						content: $('[data-content~="scroll"]', item),
						rule: $('[data-container~="rule"]', item),
						'float': $('[data-action~="scroll"]', item)
					});
					this.setScrollReaction(this.getScroll(id).data('container'));
				}
			},

			getScroll: function (id) {
				if (id) {
					return this.scrolls[id];
				} else {
					return this.scrolls;
				}
			},

			setScroll: function (id, item) {
				if (id) {
					if (item) {
						return this.scrolls[id] = item;
					} else {
						delete this.scrolls[id];
					}
				} else {
					this.scrolls = {};
				}
			},

			setRule: function (id) {
				setAllRules().then(function () {
					_class.getWrapper().trigger('rule:done');
				});

				function setAllRules () {
					var defer = $.Deferred(),
						ids = id ? $Common.getArray(id) : Object.keys($Temp.getScroll()),
						count = ids.length;

					for (var i = ids.length; i--;) {
						_setRule(ids[i], $Temp.getScroll(ids[i]));
					}

					done();
					return defer.promise();

					function _setRule (id, element) {
						if (!element.data('container')) {
							count--;
							return;
						}

						if ($Params.hasTouch) {
							if (!element.data('iscroll')) {
								var params = {
										scrollbars: true,
										shrinkScrollbars: 'scale',
										click: true
									},
									_content = element.data('content'),
									snap = !!(_content.children().length && $('[data-id]', _content).length);

								if (snap) {
									$.extend(params, {
										snap: '[data-id]'
									});
								}

								element.data({
									iscroll: new IScroll(element.get(0), params)
								});
							} else {
								element.data('iscroll').refresh();
							}

							count--;
						} else if (_class.get('replaceRules')) {
							setTimeout(function () {
								var coords = {
										H: element.data('container').height(),
										D: element.data('content').height(),
										h: element.data('rule').height()
									};

								if (!coords.D) {
									element.data({
										coords: coords
									});
								} else {
									if (!element.data('floatHeight')) {
										element.data({
											floatHeight: parseInt(element.data('float').css('min-height'))
										})
									}

									if (coords.H) {
										var delta = coords.D - coords.H;

										if (element.data('over') != delta) {
											element.data({
												over: delta
											});

											if (delta > 0) {
												if (!noScrollDelta) {
													element.addClass('bxmap-scrolling');
												}

												coords.D = element.data('content').height();
												coords.d = Math.max(
													Math.round(coords.h * coords.H / coords.D),
													element.data('floatHeight')
												);

												element.data('float').animate({
													height: coords.d + 'px',
													top: Math.round((coords.h - coords.d) * element.data('container').scrollTop() / delta) + 'px'
												}, _class.get('animationTime'));
											} else if (!noScrollDelta) {
												element.removeClass('bxmap-scrolling');
											}

											element.data({
												coords: coords
											});
										}
									}
								}

								count--;
								done();
							}, 0);
						}
					}

					function done () {
						if (!count) {
							defer.resolve();
						}
					}
				}
			},

			checkRule: function (id) {
				if (id) {
					if (this.getScroll(id)) {
						_checkRule(this.getScroll(id), id);
					}
				} else {
					for (var list = Object.keys(this.getScroll()), i = list.length; i--;) {
						_checkRule(this.getScroll(list[i]), list[i]);
					}
				}

				function _checkRule (element, id) {
					setTimeout(function () {
						var coords = element.data('coords');

						if (coords.H - $Temp.catStep < coords.D) {
							element.addClass('bxmap-scrolling');
						} else {
							element.removeClass('bxmap-scrolling');
						}
					}, 0);
				}
			},

			createLayer: function (id, item) {
				if (id && item) {
					this.layers[id] = item;
					return this.layers[id];
				}
			},

			getLayer: function (id) {
				if (id) {
					return this.layers[id];
				} else {
					return Object.keys(this.layers);
				}
			},

			setInputValue: function (value) {
				for (var list = Object.keys(this.filters), i = list.length; i--;) {
					var input = this.filters[list[i]].input,
						clear = this.filters[list[i]].clear;

					input.val(value);

					if (value) {
						clear.addClass('bxmap-active');
					} else {
						clear.removeClass('bxmap-active');
					}
				}
			},

			resize: function () {
				if (this.getScroll('cats') && this.getScroll('cats').data('few')) {
					this.getScroll('cats').css({
						bottom: this.catStep + 'px'
					});
				}

				this.setRule();
			},

			getSecondMarker: function (id) {
				if (this.secondMarker && id) {
					return this.secondMarker.id == id;
				}

				return this.secondMarker;
			},

			setSecondMarker: function (id) {
				if (this.secondMarker) {
					this.setSecondMarkerView();
					delete this.secondMarker;
				}

				if (id) {
					this.secondMarker = this.getMarker(id);
					this.setSecondMarkerView(true);
				}
			},

			setSecondMarkerView: function (status) {
				if (this.secondMarker) {
					var catID = this.getItems(this.secondMarker.id).cat;

					$Map.setMarkerView(
						this.secondMarker,
						this.getCatIcon(catID),
						catID,
						status ? this.activeStatus : this.defaultStatus
					);
				}
			},

			deleteDirection: function (status) {
				if (this.direction) {
					this.getLayer('points')
						.addClass('bxmap-none')
						.removeClass('bxmap-complete bxmap-error bxmap-collapse')
						.find('[data-action~="collapse"][data-type~="show"]').empty();
					this.getScroll('points')
						.removeClass('bxmap-scrolling')
						.data('content').empty();
					this.setDirection();
				}
			},

			setPanelsView: function (options) {
				if (!options) {
					options = {
						cats: 'show',
						objects: ''
					}
				}

				if (options.direction) {
					if (options.direction == 'show') {
						_class.getWrapper().addClass('bxmap-popup-invisible');
						this.getLayer('direction').removeClass('bxmap-none').trigger('direction:show');
						this.getLayer('cats').addClass('bxmap-none');
						this.getLayer('objects').addClass('bxmap-none');

						if (this.getLayer('subcats')) {
							this.getLayer('subcats').addClass('bxmap-none');
						}

						if (this.getLayer('points').data('complete')) {
							this.getLayer('points').removeClass('bxmap-none');
						}
					} else {
						_class.getWrapper().removeClass('bxmap-popup-invisible');
						this.getLayer('direction').addClass('bxmap-none').trigger('direction:close');
						this.getLayer('points').addClass('bxmap-none');
						this.getLayer('cats').removeClass('bxmap-none');
						this.getLayer('objects').removeClass('bxmap-none');

						if (this.getLayer('subcats')) {
							this.getLayer('subcats').removeClass('bxmap-none');
						}
					}
				} else {
					if (options.cats == 'lock' || options.objects == 'show') {
						this.getLayer('objects').removeClass('bxmap-collapse').addClass('bxmap-show');

						if (!this.slideMode) {
							if (!this.noCatsMode) {
								_class.getWrapper().addClass('bxmap-popup-invisible');
								this.getLayer('cats').removeClass('bxmap-collapse').addClass('bxmap-lock');
								this.setRule('cats');
							}
						}

						this.setRule('objects');
					} else {
						if (!this.noCatsMode) {
							_class.getWrapper().removeClass('bxmap-popup-invisible');
						}

						if (options.cats) {
							this.getLayer('objects').addClass('bxmap-collapse');

							if (options.cats == 'collapse') {
								this.getLayer('cats').removeClass('bxmap-lock').addClass('bxmap-collapse');
							} else if (options.cats == 'show') {
								this.getLayer('cats').removeClass('bxmap-collapse bxmap-lock');
								this.setRule('cats');
							}
						}

						if (options.objects) {
							this.getLayer('cats').removeClass('bxmap-lock');

							if (options.objects == 'collapse') {
								if (this.slideMode) {
									this.getLayer('objects').removeClass('bxmap-show');
								} else {
									this.getLayer('objects').addClass('bxmap-show bxmap-collapse');
								}
							} else if (options.objects == 'invisible') {
								this.getLayer('objects').removeClass('bxmap-show');

								if (!this.slideMode) {
									this.getLayer('objects').addClass('bxmap-collapse');
								}
							}
						}
					}
				}
			},

			toggleClearButton: function () {
				var status = $Temp.getActiveCats().length;

				this.getScroll('cats').data({
					few: !status
				}).animate({
					bottom: status ? this.catStep : 0
				}, _class.get('animationTime'), function () {
					$Temp.setRule('cats');
				});

				if (status) {
					this.clearBlock.animate({
						height: (status ? this.buttonHeight : 0)
					}, _class.get('animationTime'));
				} else {
					this.clearBlock.css({
						height: (status ? this.buttonHeight : 0)
					});
				}
			}
		});

		return {
			requires: requires,
			options: versionOptions
		};
	}

	function start () {
		$Temp.setNoCatsMode(_class.get('noCats'));
		$Temp.setNoPanelsMode(_class.get('noPanels'));
		setWrapper();
		createWrapper();
		createBalloon();
		_class.getWrapper().addClass('bxmap-show-wrapper');
		$Map.createMap({
			container: $Temp.mapContainer,
			scroll: true,
			mouse: true,
			zoom: true
		});

		var data = checkData();

		if (data) {
			if (!$Temp.slideMode || $Temp.overlayMode) {
				loading.wrapper = $Common.createElement('*div', 'bxmap-loading-informer').appendTo(_class.getWrapper());
				_class.getWrapper().addClass('bxmap-loading');
			}

			$Temp.parseData(data, function (queryParams) {
				createData(queryParams, function (info) {
					if (loading.wrapper) {
						loading.wrapper.remove();
						_class.getWrapper().removeClass('bxmap-loading');
					}

					data = null;
					_class.set('cats');
					_class.set('items');
					_class.getWrapper().trigger('module:complete', ['GeoMapp']);
				});
			});
		} else {
			_class.getWrapper().removeAttr('style');
			_class.showError('NO_ITEMS');
			_class.getWrapper().trigger('module:complete', ['GeoMapp']);
		}

		function checkData () {
			var options = {
					cats: _checkData('cats'),
					items: _checkData('items'),
					query: _class.get('query')
				},
				filter = _class.get('filter'),
				title = _class.get('title'),
				exist = _class.get('exist');

			if (!options.items && !options.cats && !options.query) {
				return null;
			}

			if (filter) {
				switch ($Common.getType(filter)) {
					case 'String':
						options.filter = {
							query: filter
						}

						break;
					case 'Object':
						options.filter = filter;
						break;
				}
			}

			if (title) {
				options.title = title;
			}

			if (exist) {
				options.exist = exist;
			}

			return options;

			function _checkData (name) {
				var _data = _class.get(name);

				if (_data && $Common.getType(_data, 'object') && Object.keys(_data).length) {
					return _data;
				}
			}
		}
	}

	function setWrapper () {
		var _wrapper = _class.getWrapper(),
			extraNarrowWidth = parseInt(_class.get('extraNarrowWidth')),
			narrowWidth = parseInt(_class.get('narrowWidth')),
			overlayType = _class.get('overlayType'),
			classes = [],
			_testscroll = $Common.createElement('*div').css({
				position: 'absolute',
				zIndex: -1,
				top: '-200px',
				left: '-200px',
				width: '100px',
				height: '100px',
				overflowY: 'scroll'
			}).appendTo(document.body);

		if (overlayType) {
			if (/\bslide\b/i.test(overlayType.toString())) {
				$Temp.slideMode = true;
			} else {
				$Temp.standardMode = true;
			}

			$Temp.overlayMode = /\bshow\b/i.test(overlayType.toString());
		} else {
			$Temp.standardMode = true;
		}

		if ($Params.hasTouch) {
			classes.push('bxmap-touchscreen');
		}

		if (_class.get('replaceRules')) {
			$(window).on('resize', $Temp.resize.bind($Temp));
		} else {
			classes.push('bxmap-native-rules');

			if (_testscroll.get(0).clientWidth == _testscroll.get(0).offsetWidth) {
				noScrollDelta = true;
				classes.push('bxmap-mac');
			}
		}

		_testscroll.remove();
		_wrapper.addClass(classes.join(' '));

		if (_class.get('noCatIcons')) {
			_wrapper.addClass('bxmap-no-image');
		} else {
			var icon = $Temp.getIcon(),
				width = icon.logo[0],
				height = icon.logo[1],
				delta = 30 - width,
				offset = delta > 0 ? 40 : width + 10;

			$Common.createRule(
				[
					'.bxmap-item:before',
					'.bxmap-list-scroll .bxmap-list-item:before'
				].join(','),
				'background-image:url(' + icon.url + ');'
			);
			$Common.createRule(
				[
					'.bxmap-item:before',
					'.bxmap-list-scroll .bxmap-list-item:before'
				].join(','),
				[
					'width:' + width + 'px;',
					'height:' + height + 'px;'
				].join('')
			);
			$Common.createRule(
				'.bxmap-full .bxmap-item:before',
				[
					'left:-' + (width + 2) + 'px;',
					'margin-top:-' + height / 2 + 'px;'
				].join('')
			);
			$Common.createRule(
				'.bxmap-full .bxmap-objects .bxmap-item:before',
				'left:' + delta + 'px;'
			);
			$Common.createRule(
				'.bxmap-full .bxmap-cat-list',
				'padding-left:' + width + 'px;'
			);
			$Common.createRule(
				'.bxmap-full .bxmap-list-clear',
				'left:' + width + 'px;'
			);
		}

		if ($Temp.slideMode) {
			_wrapper.addClass('bxmap-slide');
		} else {
			_wrapper.addClass('bxmap-standard');
			$Common.createRule(
				'.bxmap-wrapper.bxmap-standard',
				'height:' + _class.get('height') + 'px;'
			);

			if (!_class.get('noCatIcons')) {
				$Common.createRule(
					[
						'.bxmap-show-cats .bxmap-objects .bxmap-item > .bxmap-item-wrapper',
						'.bxmap-list-scroll .bxmap-list-item'
					].join(','),
					'padding-left:' + offset + 'px;'
				);
				$Common.createRule(
					'.bxmap-show-cats .bxmap-sublist',
					'margin-left:' + offset + 'px;'
				);
			}

			$(window).on('resize', setWidth);
			setTimeout(function () {
				setWidth();
			}, 0);
		}

		function setWidth () {
			var width = _wrapper.width();

			if (width < narrowWidth) {
				_wrapper.addClass('bxmap-narrow');

				if (width < extraNarrowWidth) {
					_wrapper.addClass('bxmap-extra-narrow');
				} else {
					_wrapper.removeClass('bxmap-extra-narrow');
				}
			} else {
				_wrapper.removeClass('bxmap-narrow bxmap-extra-narrow');
			}
		}
	}

	function createWrapper () {
		var _wrapper = _class.getWrapper().append(
				$Temp.mapContainer,
				$Common.createElement('*div', 'bxmap-overlay-head', {
					'data-container': 'title'
				}).append(
					$Temp.wrapperTitle,
					$Common.createElement('*div', 'bxmap-overlay-button', {
						'data-container': 'toggle'
					}).append(
						$Common.createElement('*span', 'bxmap-overlay-item bxmap-overlay-close', {
							'data-action': 'toggle',
							'data-type': 'close',
							title: _class.get('interfaceText').closeMap
						}).text(_class.get('interfaceText').closeMap)
					)
				)
			),
			toggle;

		_class.setTitle();

		if ($Temp.slideMode) {
			$Temp.createLayer(
				'subcats',
				$Common.createElement('*div', 'bxmap-section bxmap-list bxmap-subcats', {
					'data-container': 'collapse',
					'data-panel': 'subcats'
				}).append(
					createTitle({
						title: _class.get('interfaceText').subcatsTitle,
						subtitle: _class.get('interfaceText').catsTitle,
						close: _class.get('interfaceText').closeList
					}),
					$Common.createElement('*dd', 'bxmap-section-body', {
						'data-target': 'collapse',
						'data-container': 'select'
					}).append(
						createFilterForm('subcats'),
						$Temp.setScroll('subcats', $Temp.createScroll({
							content: $Temp.createList('subcats')
						}))
					)
				)
			);
			toggle = $Common.createElement('*span', 'bxmap-toggle-panel', {
				'data-action': 'toggle',
				'data-type': 'panel'
			});
		}

		$Temp.createLayer(
			'cats',
			$Common.createElement('*div', 'bxmap-section bxmap-list bxmap-cats bxmap-full', {
				'data-container': 'collapse',
				'data-panel': 'cats'
			}).append(
				createTitle({
					show: _class.get('interfaceText').catsTitle,
					hide: _class.get('interfaceText').collapsePanel
				}),
				$Common.createElement('*dd', 'bxmap-section-body', {
					'data-target': 'collapse',
					'data-container': 'select'
				}).append(
					createFilterForm('cats'),
					$Temp.setScroll('cats', $Temp.createScroll({
						content: $Temp.createList('cats')
					})),
					$Temp.clearBlock
				)
			)
		);

		$Temp.createLayer(
			'objects',
			$Common.createElement('*dl', 'bxmap-section bxmap-objects', {
				'data-container': 'collapse',
				'data-panel': 'objects'
			}).append(
				createTitle({
					show: _class.get('interfaceText').objectsTitle,
					close: _class.get('interfaceText').closeList,
					hide: _class.get('interfaceText').collapsePanel
				}),
				$Common.createElement('*dd', 'bxmap-section-body', {
					'data-target': 'collapse'
				}).append(
					createFilterForm('objects'),
					$Temp.setScroll('objects', $Temp.createScroll({
						content: $Temp.createList('items')
					}))
				)
			)
		);

		$Temp.createLayer(
			'points',
			$Common.createElement('*dl', 'bxmap-section bxmap-points bxmap-none', {
				'data-container': 'collapse',
				'data-panel': 'points'
			}).append(
				createTitle({
					show: '',
					hide: _class.get('interfaceText').collapsePanel,
					close: _class.get('interfaceText').closeList
				}),
				$Common.createElement('*dd', 'bxmap-section-body', {
					'data-target': 'collapse'
				}).append(
					$Temp.setScroll('points', $Temp.createScroll({
						classes: 'bxmap-single-scroll'
					}))
				)
			)
		);

		$Temp.getContainer().append(
			$Common.createElement('*ul', 'bxmap-controls').append(
				$Common.createElement('*li', 'bxmap-controls-button bxmap-toggle-fullscreen', {
					'data-action': 'toggle',
					title: _class.get('interfaceText').showFullScreen
				}).append($Common.createElement('*span', 'bxmap-toggle-inner')),
				$Common.createElement('*li', 'bxmap-controls-button bxmap-zoom-decrease', {
					'data-action': 'zoom',
					'data-type': 'decrease',
					title: _class.get('interfaceText').decreaseZoom
				}),
				$Common.createElement('*li', 'bxmap-controls-button bxmap-zoom-increase', {
					'data-action': 'zoom',
					'data-type': 'increase',
					title: _class.get('interfaceText').increaseZoom
				})
			),
			$Temp.getLayer('cats'),
			$Temp.getLayer('subcats'),
			$Temp.getLayer('objects'),
			toggle,
			createDirectionForm(),
			$Temp.getLayer('points')
		).appendTo(_wrapper);

		if (!$Temp.slideMode) {
			$Temp.buttonHeight = parseInt($Temp.getLayer('cats').find('[data-container~="clear"] [data-action~="clear"]').css('height'));
		}

		$Temp.getContainer().on($Params.altEvents.start, '[data-container~="input"] [data-action~="clear"]', function (e) {
				var _form = $(this).closest('[data-container~="input"]'),
					_input = _form.find('[data-action~="filter"]');

				setTimeout(function () {
					_input
						.val('')
						.trigger('focus')
						.trigger('check:value');
				}, 0);
			});

		_wrapper
			.on('cluster:done', function (e, clusterLength) {
				clusterDone = true;
			})
			.on('click', '[data-action~="toggle"]', function () {
				var _self = $(this),
					type = _self.data('type');

				if (type == 'panel') {
					$Temp.getContainer().toggleClass('bxmap-slide-collapse');
				} else if (type == 'multilist') {
					_self.closest('[data-container~="multilist"]').toggleClass('bxmap-active');
				} else {
					_class.toggleOverlayMode(type);
				}
			})
			.on('click', '[data-action~="collapse"]', function (e) {
				var _self = $(this),
					_container = _self.closest('[data-container~="collapse"]'),
					options = {},
					panelType = _container.data('panel'),
					buttonType = _self.data('type');

				switch (panelType) {
					case 'subcats':
						if ($Temp.slideMode && buttonType == 'close') {
							hideGroupCats();
						}

						break;
					case 'cats':
						options[panelType] = buttonType == 'show' ? 'show' : 'collapse';
						$Temp.setPanelsView(options);
						break;
					case 'objects':
						if ($Temp.slideMode) {
							if (buttonType == 'close') {
								options[panelType] = 'collapse';
								hideActiveObjects();
							}
						} else {
							options[panelType] = buttonType == 'show' ? 'show' : 'collapse';
							$Temp.setPanelsView(options);
						}

						break;
					case 'direction':
						options[panelType] = buttonType == 'show' ? 'show' : 'collapse';
						$Temp.setPanelsView(options);
						break;
					case 'points':
						if (buttonType == 'show') {
							_container.removeClass('bxmap-collapse');
						} else if (buttonType == 'hide') {
							_container.addClass('bxmap-collapse');
						} else {
							_container.addClass('bxmap-none');
						}

						break;
				}
			})
			.on('click', '[data-action~="zoom"]', function (e) {
				$Map.changeZoom($(this).data('type') == 'increase');
			})
			.on('click', '[data-action~="list"]', function (e) {
				e.stopPropagation();

				var id = $(this).data('id');

				if (id) {
					if ($Temp.getActiveCats(id)) {
						hideActiveObjects(id);
					} else {
						$Temp.parseData({
							query: {
								cat: id
							}
						}, function (queryParams) {
							$Temp.checkCounts(queryParams, function () {
								if ($Temp.slideMode && $Temp.getActiveCats().length == 1) {
									$Temp.getContainer().addClass('bxmap-slide-collapse');
								}
							})
						});
					}
				}
			})
			.on('click', '[data-action~="grouplist"]', function (e) {
				var id = $(this).data('id');

				e.stopPropagation();

				if (id) {
					if ($Temp.getActiveGroupCats(id)) {
						hideGroupCats(id);
					} else {
						showGroupCats(id);
					}
				}
			})
			.on('click', '[data-action~="sublist"] > [data-item~="name"]', function (e) {
				e.stopPropagation();
				$(this).closest('[data-action~="sublist"]').toggleClass('bxmap-active');
				$Temp.setRule('cats');
			})
			.on('click', '[data-container~="clear"] [data-action~="clear"]', function (e) {
				hideActiveObjects();
			})
			.on($Params.altEvents.start, '[data-action~="geo"]', function (e) {
				var id = $(this).closest('[data-item]').data('id');

				if ($Temp.getActiveID(id)) {
					$Map.panToMarker($Temp.getMarker(id));
				} else {
					$Temp.toggleActiveItem(id);

					if (!$Temp.slideMode && !$Temp.noCatsMode) {
						$Temp.setPanelsView({
							objects: 'collapse'
						});
					}
				}
			})
			.on('objectMarker:click eventMarker:click', function (e, id, emulation) {
				var item = $Temp.getItems(id);

				if (item) {
					if ($Temp.directionMode) {
						if ($Temp.getActiveID(id)) {
							return;
						}

						//$Temp.deleteDirection();
						toggleCluster();

						if ($Temp.selectMarkerMode) {
							if ($Temp.getSecondMarker(id)) {
								return;
							}

							$Temp.setSecondMarker(id);
						} else {
							if ($Temp.getSecondMarker(id)) {
								$Temp.setSecondMarker();
							}

							resetActiveMarker();
							setActiveMarker(
								id,
								item.cat,
								item,
								emulation
							);
						}

						$Temp.getLayer('direction').trigger('set:direction');
					} else {
						if ($Temp.getActiveID()) {
							if ($Temp.getActiveID(id)) {
								resetActiveMarker(true);
								$Balloon.close();
								return;
							} else {
								toggleCluster();
								resetActiveMarker();
							}
						}

						setActiveMarker(
							id,
							item.cat,
							item,
							emulation
						);

						if ($Temp.slideMode) {
							$Temp.getContainer().addClass('bxmap-slide-collapse');
						}
					}
				}
			})
			.on('routeMarker:click', function (e, id) {
				var item = $Temp.getItems(id),
					routeID = item.parentItem || id;

				if ($Temp.getActiveRoute(routeID)) {
					if ($Temp.getActiveID(id)) {
						resetActiveRouteMarker(getRoutePointStatus($Temp.getActiveID()));
						$Common.replaceState({
							query: {
								item: null
							}
						});
					} else {
						if ($Temp.getActiveID()) {
							resetActiveRouteMarker(getRoutePointStatus($Temp.getActiveID()));
						}

						select();
					}
				} else {
					setActiveRoute(routeID, id);
					select();

					if ($Temp.slideMode) {
						$Temp.getContainer().addClass('bxmap-slide-collapse');
					}
				}

				function select () {
					setActiveRouteMarker(
						id,
						routeID,
						item,
						getRoutePointStatus(id, true)
					);
					_class.trackItem({
						itemID: id,
						item: item,
						routeID: routeID,
						route: $Temp.getItems(routeID)
					});
					showMapObjectsInfo();
				}
			})
			.on('routeMarker:hover', function (e, id) {
				var routeID = id.split('_')[0];

				if (!$Temp.getActiveRoute(routeID)) {
					$Map.setRouteView(routeID, 'hover');
				}
			})
			.on('routeMarker:out', function (e, id) {
				var routeID = $Temp.getItems(id).parentItem;

				if (!$Temp.getActiveRoute(routeID)) {
					$Map.setRouteView(routeID);
				}
			})
			.on('route:hover', function (e, id) {
				if (!$Temp.getActiveRoute(id)) {
					$Map.setRouteView(id, 'hover');
				}
			})
			.on('route:out', function (e, id) {
				if (!$Temp.getActiveRoute(id)) {
					$Map.setRouteView(id);
				}
			})
			.on('route:click', function (e, id) {
				if ($Temp.getActiveRoute(id)) {
					resetActiveRoute();
				} else {
					$Temp.toggleActiveItem(id);
				}
			})
			.on('click', '[data-action~="direction"]', function (e) {
				_class.setDirectionMode();
			});

		if (!$Params.hasTouch && _class.get('replaceRules')) {
			$Temp.getContainer()
				.on('mousedown', '[data-action~="scroll"]', function (e) {
					var _self = $(this),
						_wrapper = _self.closest('[data-wrapper~="scroll"]'),
						coords = _wrapper.data('coords');

					coords.T = _self.position().top;
					coords.Y = e.clientY;
					$Temp.getContainer().on('mouseup', _up).on('mousemove', _move);

					function _up (e) {
						$Temp.getContainer().off('mouseup', _up).off('mousemove', _move);
					}

					function _move (e) {
						e.preventDefault();
						e.stopPropagation();

						coords.delta = e.clientY - coords.Y;
						_wrapper.trigger('set:scroll');
					}
				})
				.on('mousedown', '[data-container~="rule"]', function (e) {
					var _self = $(e.target),
						_wrapper = _self.closest('[data-wrapper~="scroll"]'),
						_float = _wrapper.data('float'),
						coords = _wrapper.data('coords');

					if (!_self.is(_float)) {
						var t = _float.position().top,
							p = e.clientY - e.target.getBoundingClientRect().top - t;

						coords.T = t;
						coords.delta = coords.d * p / Math.abs(p);
						_wrapper.trigger('set:scroll', [true]);
					}
				})
				.on('set:scroll', '[data-wrapper~="scroll"]', function (e, animation) {
					var _wrapper = $(this),
						_container = _wrapper.data('container'),
						_rule = _wrapper.data('rule'),
						_float = _wrapper.data('float'),
						coords = _wrapper.data('coords'),
						t = coords.T + coords.delta,
						T = Math.round(t * coords.D / coords.H);

					if (t > coords.h - coords.d) {
						t = coords.h - coords.d;
						T = coords.D - coords.H;
					} else if (t < 0) {
						t = T = 0;
					}

					if (animation) {
						_rule.addClass('bxmap-active');
						_float.animate({
							top: t + 'px'
						}, 400, function () {
							_rule.removeClass('bxmap-active');
						});
						_container.animate({
							scrollTop: T
						}, 400, function () {

						});
					} else {
						_float.css({
							top: t + 'px'
						});
						_container.scrollTop(T);
					}
				});
		}

		function createDirectionForm () {
			var points = [],
				locations = {},
				options = {},
				objectValues,
				secondValues,
				downShift,
				_input = $Common.createElement('input', 'bxmap-direction-filter bxmap-filter-input', {
					name: 'inputtext',
					type: 'text',
					placeholder: _class.get('interfaceText').from,
					title: _class.get('interfaceText').from,
					autocomplete: 'on',
					'data-action': 'filter'
				}),
				choiceInput = $Common.createElement('input', 'bxmap-point-choice-input', {
					type: 'checkbox'
				});
				_choice = $Common.createElement('label', 'bxmap-point-choice', {
					title: _class.get('interfaceText').choiceText,
					'data-action': 'choice'
				}).append(choiceInput),
				_marker = $Common.createElement('*div', 'bxmap-direction-select', {
					'data-type': 'marker'
				}).text(_class.get('interfaceText').choiceMarker),
				_object = $Common.createElement('*div', 'bxmap-direction-select', {
					'data-target': 'point'
				}),
				_reverse = $Common.createElement('input', 'bxmap-direction-reverse-input', {
					name: 'reverse',
					type: 'checkbox'
				}),
				_select = createTypes(_class.get('routeType')[_class.get('mapType')]),
				_button = $Common.createElement('button', 'bxmap-direction-create', {
					type: 'submit',
					disabled: true,
					'data-action': 'create'
				}).text(_class.get('interfaceText').createRoute),
				_clear = $Common.createElement('button', 'bxmap-filter-clear', {
					type: 'button',
					title: _class.get('interfaceText').clearField,
					'data-action': 'clear'
				}).text(_class.get('interfaceText').clearField),
				_points = $Common.createElement('*div', 'bxmap-direction-points', {
					'data-container': 'point'
				}).append(
					$Common.createElement('*div', 'bxmap-direction-point bxmap-input-point', {
						'data-item': 'point',
						'data-type': 'input'
					}).append(
						_choice,
						$Common.createElement('*div', 'bxmap-direction-start', {
							'data-type': 'address'
						}).append(
							_input,
							$Common.createElement('*div', 'bxmap-filter-field').append(_clear)
						),
						_marker
					),
					$Common.createElement('*div', 'bxmap-direction-point bxmap-filter-point', {
						'data-item': 'point',
						'data-type': 'select'
					}).append(_object),
					$Common.createElement('label', 'bxmap-direction-reverse', {
						'data-action': 'reverse',
						title: _class.get('interfaceText').reverseDirection
					}).append(
						_reverse,
						$Common.createElement('*span', 'bxmap-direction-reverse-title').text(_class.get('interfaceText').reverseDirection)
					)
				),
				_form = $Temp.createLayer(
					'direction',
					$Common.createElement('*dl', 'bxmap-section bxmap-direction bxmap-none', {
						'data-container': 'collapse direction input',
						'data-panel': 'direction'
					}).append(
						createTitle({
							close: _class.get('interfaceText').closeList
						}).append(
							$Common.createElement('*span', 'bxmap-section-subtitle').text(_class.get('interfaceText').directionTitle)
						),$Common.createElement('*dd', 'bxmap-section-body', {
							'data-target': 'direction'
						}).append(
							$Common.createElement('form', 'bxmap-direction-form', {
								'data-container': 'route'
							}).append(
								_button,
								_select,
								_points
							)
						)
					)
				);

			options.reverse = _reverse.is(':checked');

			$(document).on('keydown', setMode);
			$(document).on('keyup', function () {
				$(document).on('keydown', setMode);
			});

			function setMode (e) {
				if ($Temp.directionMode && e.keyCode == 16) {
					if ($Temp.selectMarkerMode) {
						if (downShift) {
							choiceInput.trigger('click');
							$(document).off('keydown', setMode);
						}
					} else {
						if (!downShift) {
							choiceInput.trigger('click');
							$(document).off('keydown', setMode);
						}
					}
				}
			}

			_form
				.on('check:value', function (e, params) {
					if (params && params.value.length) {
						_clear.addClass('bxmap-active');
					} else {
						_clear.removeClass('bxmap-active');
					}

					checkForm();
				})
				.on('set:direction', function (e) {
					if ($Temp.directionMode) {
						var id = $Temp.getActiveID(),
							item = $Temp.getItems(id),
							ids = Object.keys($Temp.getActiveIDs()),
							status;

						if (objectValues) {
							if (ids.length) {
								if (objectValues.ids.length) {
									for (var i = ids.length, status_; i--;) {
										if (objectValues.ids.indexOf(ids[i]) >= 0) {
											status_ = true;
											break;
										}
									}

									status = !status_;
								} else {
									status = ids.indexOf(objectValues.id) < 0;
								}
							} else {
								if (objectValues.ids.length) {
									status = objectValues.ids.indexOf(id) < 0;
								} else {
									status = id != objectValues.id;
								}
							}
						} else {
							status = true;
						}

						locations.object = {
							id: id,
							ids: ids,
							point: item.point,
							address: item.values.address,
							name: item.values.name + ', ' + item.values.address
						};
						points = [locations.object];
						_object.text(item.values.name);

						if ($Temp.selectMarkerMode) {
							if ($Temp.secondMarker) {
								var _id = $Temp.secondMarker.id,
									_item = $Temp.getItems(_id);

								_marker
									.text(_item.values.name)
									.addClass('bxmap-active');
								locations.second = {
									point: _item.point,
									address: _item.values.address,
									name: _item.values.name + ', ' + _item.values.address
								};
							} else {
								_marker
									.text(_class.get('interfaceText')
									.choiceMarker).removeClass('bxmap-active');
							}
						} else {
							//delete locations.second;
						}

						//checkForm(status);
						checkForm();

						function getStatus () {
							
						}
					}
				})
				.on('change', '[data-action~="choice"]', function (e) {
					var status = e.target.checked;

					if (status) {
						_choice.addClass('bxmap-active');
						$Temp.selectMarkerMode = true;
						downShift = true;
					} else {
						_choice.removeClass('bxmap-active');
						delete $Temp.selectMarkerMode;
						downShift = false;
					}

					$Temp.setSecondMarkerView(status);
					_form.trigger('set:direction');
					e.target.blur();
				})
				.on('change', '[data-action~="type"]', function (e) {
					$(this).addClass('bxmap-active').siblings().filter('.bxmap-active').removeClass('bxmap-active');
					points = [locations.object];
					checkForm();
				})
				.on('change', '[data-action~="reverse"]', function (e) {
					var $label = $(this),
						$input = $label.find('input'),
						direction = $input.is(':checked') ? 'to' : 'from';

					$input.trigger('blur');
					_points.find('[data-item~="point"]').eq(1).prependTo(_points);
					_input.attr({
						placeholder: _class.get('interfaceText')[direction],
						title: _class.get('interfaceText')[direction]
					});
					checkForm();
				})
				.on('submit', '[data-container~="route"]', function (e) {
					e.preventDefault();
					options = {
						travelMode: _select.find(':checked').val().toUpperCase(),
						reverse: _reverse.is(':checked')
					};

					if ($Temp.selectMarkerMode) {
						if (locations.second) {
							_getRoute(locations.second);
							delete locations.custom;
						}
					} else if (_input.val()) {
						locations.custom = _input.val();
						$Map.getGeoPoint(locations.custom).then(function (response) {
							if (response.error) {
								showDirectionError(response.error);
							} else {
								$Temp.setSecondMarker();
								_getRoute(response);
							}
						});
					}

					function _getRoute (point) {
						var _loading = $Common.createElement('*div', 'bxmap-loading-informer'),
							delta = 1000,
							start = new Date(),
							geoPoints = [],
							edge = ['start', 'end'];

						points.unshift(point);
						$Temp.getLayer('points')
							.addClass('bxmap-loading')
							.removeClass('bxmap-none bxmap-complete bxmap-error bxmap-collapse')
							.append(_loading)
							.find('[data-action~="collapse"][data-type~="show"]').text(_class.get('routeMessages').wait);
						$Temp.getScroll('points').data('content').empty();
						_button.attr('disabled', true);

						if (options.reverse) {
							points.reverse();
							edge.reverse();
						}

						$Map.getRoute(
							points,
							options.travelMode,
							$Temp.getDirectionIcon(),
							_class.get('directionOptions'),
							edge[0]
						).then(function (response) {
							var current = new Date() - start;

							if (current > delta) {
								current = delta;
							}

							setTimeout(function () {
								if (response.error) {
									showDirectionError(response.error);
								} else {
									var text = options.travelMode.toLowerCase() == 'walking' ? 'toWalk' : 'toDrive',
										_ul = $Common.createElement('*ul', 'bxmap-route-points');

									insertPoint(
										' bxmap-action bxmap-start',
										points[0].name || points[0].address
									).appendTo(_ul);

									response.result.segments.forEach(function (segment) {
										insertPoint(
											segment.action ? ' bxmap-action bxmap-' + segment.action : '',
											segment.text,
											segment.humanLength
										).appendTo(_ul);
									});

									insertPoint(
										' bxmap-action bxmap-end',
										points[1].name || points[1].address
									).appendTo(_ul);

									_loading.remove();
									$Temp.getLayer('points')
										.data({
											complete: true
										})
										.addClass('bxmap-complete')
										.removeClass('bxmap-loading')
										.find('[data-action~="collapse"][data-type~="show"]')
										.empty()
										.append(
											_class.get('interfaceText')[text] + ' ',
											response.result.humanDuration,
											' (' + response.result.humanLength + ')'
										);

									objectValues = {
										id: locations.object.id,
										ids: locations.object.ids
									};

									if (locations.second) {
										secondValues = {
											id: locations.second.id,
											ids: locations.second.ids
										};
									}

									$Temp.getScroll('points').data('content').append(_ul);
									$Temp.setRule('points');
									$Temp.setDirection(response.result);
									_class.trackDirection({
										duration: response.result.duration,
										length: response.result.length,
										bounds: response.result.bounds,
										polyLine: response.result.polyLine,
										segments: response.result.segments,
										steps: response.steps
									});
								}

								function insertPoint (action, text, distance) {
									var _item = $Common.createElement('*li', 'bxmap-point' + action).append(
										$Common.createElement('*div', 'bxmap-point-text').html(text)
									)

									if (distance) {
										_item.append(
											$Common.createElement('*div', 'bxmap-point-distance').html(distance)
										);
									}

									return _item;
								}
							}, delta - current);
						});
					}
				})
				.on('direction:show', function () {
					$Temp.directionMode = true;
					showExistDirection();
					_points.css({
						marginRight: _checkWidth(_button) + _checkWidth(_select) + 'px'
					});

					function _checkWidth (element) {
						return element.get(0).offsetWidth + parseInt(element.css('margin-left')) + parseInt(element.css('margin-right'))
					}
				})
				.on('direction:close', function () {
					hideExistDirection(true);
					delete $Temp.directionMode;
				});

			processingInput({
				element: _input,
				context: _form,
				callback: reactInput
			});
			return _form;

			function showExistDirection () {
				if ($Temp.getDirection()) {
					$Temp.setSecondMarkerView(true);
					$Temp.setDirectionView(true);
					$Temp.getLayer('points').removeClass('bxmap-none');
				}
			}

			function hideExistDirection (status) {
				if ($Temp.getDirection()) {
					if (status) {
						$Temp.setSecondMarkerView();
					}

					$Temp.setDirectionView();
					$Temp.getLayer('points').addClass('bxmap-none');
				}
			}

			function checkForm (status) {
				if (!status) {
					if (locations.second || _input.val()) {
						if (_input.val() && _input.val() != locations.custom) {
							status = true;
						}

						if (locations.second && locations.second != locations.custom) {
							status = true;
						}

						if (_reverse.is(':checked') != options.reverse) {
							status = true;
						}

						if (_select.find(':checked').val().toUpperCase() != options.travelMode) {
							status = true;
						}
					}
				}

				if (status) {
					_button.removeAttr('disabled');
					hideExistDirection();
				} else {
					_button.attr('disabled', true);
					showExistDirection();
				}
			}

			function createTypes (_data, type) {
				var element = $Common.createElement('*dt', 'bxmap-direction-types', {
					'data-container': 'type'
				});

				Object.keys(_data).forEach(function (i) {
					element.append(
						$Common.createElement('label', 'bxmap-direction-type bxmap-' + _data[i], {
							title: _class.get('interfaceText')[_data[i]],
							'data-action': 'type',
							'data-type': _data[i]
						}).append(
							$Common.createElement('input', 'bxmap-direction-type-input', {
								name: 'type',
								type: 'radio',
								value: _data[i].toUpperCase()
							}),
							$Common.createElement('*span', 'bxmap-direction-type-title').html(_class.get('interfaceText')[_data[i]])
						)
					);
				});

				var _labels = element.find('[data-action~="type"]'),
					_active = _labels.eq(0);

				if (type) {
					_active = _labels.filter('[data-type~="' + type + '"]');
				}

				_active.addClass('bxmap-active');
				_active.find('input').attr('checked', true);
				options.travelMode = _active.data('type').toUpperCase();
				return element;
			}

			function showDirectionError (error) {
				$Temp.getLayer('points')
					.removeData('complete')
					.removeClass('bxmap-none bxmap-loading')
					.addClass('bxmap-error')
					.find('[data\-action~="collapse"][data\-type~="show"]').text(_class.get('interfaceText').error);

				$Temp.getScroll('points')
					.removeClass('bxmap-scrolling')
					.data('content')
					.empty()
					.append(
						$Common.createElement('*div', 'bxmap-error-message-container').append(
							$Common.createElement('*div', 'bxmap-error-message').html(_class.get('routeMessages')[error])
						)
					);
			}
		}

		function createFilterForm (name) {
			var _input = $Common.createElement('input', 'bxmap-filter-input', {
					name: 'inputtext',
					type: 'text',
					placeholder: _class.get('interfaceText').placeHolder,
					autocomplete: 'off',
					'data-action': 'filter'
				}),
				_clear = $Common.createElement('button', 'bxmap-filter-clear', {
					type: 'button',
					title: _class.get('interfaceText').clearField,
					'data-action': 'clear'
				}).text(_class.get('interfaceText').clearField),
				_form = $Common.createElement('form', 'bxmap-filter-form', {
					'data-name': name,
					'data-container': 'filter input'
				}).append(
					_input,
					$Common.createElement('*div', 'bxmap-filter-field').append(_clear)
				);

			$Temp.filters[name] = {
				form: _form,
				clear: _clear,
				input: _input
			};
			_input
				.on('focus', function (e) {
					_form.addClass('bxmap-active');
				})
				.on('blur', function (e) {
					_form.removeClass('bxmap-active');
				});

			_form.on('check:value', function (e, params) {
				var value = params ? params.value : '';

				clearTimeout($Temp.filterDelay);

				if (value) {
					$Temp.filterDelay = setTimeout(function () {
						_filter(value);
					}, 200);
				} else {
					_filter();
				}

				function _filter () {
					_class.filterMapObjects({
						mode: 'replace',
						query: {
							include: {
								name: value
							}
						}
					});
				}
			})
			.on('submit', function (e) {
				e.preventDefault();
			});

			processingInput({
				element: _input,
				context: _form,
				callback: reactInput
			});

			return _form;
		}

		function getRoutePointStatus (id, active) {
			var route = $Temp.getItems($Temp.getItems(id).parentItem || id),
				index = route.points.indexOf(id);

			if (index == 0) {
				status = active ? 'start' : route.status;
			} else if (index == route.points.length - 1) {
				status = active ? 'end' : route.status;
			} else {
				status = active ? 'super' : route.status;
			}

			return status;
		}
	}

	function createData (query, callback) {
		createCatList(
			$Temp.changeCatOrder({
				type: 'group'
			}),
			$Temp.changeCatOrder({
				type: 'single'
			})
		);
		setView();

		function setView () {
			var catList = Object.keys($Temp.getCurrentCats()),
				itemList = $Temp.getCurrentItems();

			$Temp.setCatsMode();
			if (itemList.length || catList.length) {
				var groupList = $Temp.getGroupCats(),
					singleList = $Temp.getSingleCats(),
					activeCats = $Temp.getActiveCats();

				_class.removeError();
				$Temp.overlayMode = $Temp.overlayMode || query.type == 'geo' || query.type == 'show';

				if (_class.get('noCats')) {
					setNoCats();
				} else {
					if ($Temp.slideMode) {
						if (groupList.length == 1) {
							if (singleList.length == 1) {
								setNoCats();
							} else {
								if ($Temp.noCatsMode) {
									$Temp.setNoCatsMode();
									$Temp.setPanelsView({
										objects: 'collapse'
									});
								}

								$Temp.setSubCatsMode();
								$Temp.setActiveGroupCats(groupList[0]);
							}
						} else {
							if ($Temp.noCatsMode) {
								$Temp.setNoCatsMode();
								$Temp.setPanelsView({
									objects: 'collapse'
								});

								if (activeCats.length) {
									query = $Common.getQuery({
										query: query,
										add: {
											cat: activeCats
										}
									});
								}
							}

							if ($Temp.getActiveGroupCats().length || activeCats.length) {
								$Temp.getContainer().addClass('bxmap-toggle-subcats bxmap-slide-collapse');
							}

							if ($Temp.getVisibleItems().length) {
								$Temp.getContainer().addClass('bxmap-toggle-objects');
							}
						}
					} else {
						if (singleList.length == 1) {
							setNoCats();
						} else {
							if ($Temp.noCatsMode) {
								$Temp.setNoCatsMode();
								$Temp.setPanelsView({
									objects: 'collapse'
								});
							}

							_class.getWrapper().addClass('bxmap-show-cats');
						}
					}
				}

				if (!$Temp.slideMode || $Temp.overlayMode) {
					if (!$Temp.slideMode) {
						$Temp.setStep();
					}

					if ($Temp.overlayMode) {
						_class.toggleOverlayMode('show');
					} else if (!$Temp.slideMode) {
						$Temp.setRule();
						$Map.refreshMap(true);
					}
				}

				showActiveObjects(query, true, callback);
			} else {
				var info = _class.getMapObjects();

				showMapObjectsInfo(info);

				if (callback) {
					callback(info);
				}
			}

			function setNoCats () {
				if (!$Temp.noCatsMode) {
					$Temp.setNoCatsMode(true);
				}

				if (itemList.length) {
					$Temp.setVisibleItems({
						list: itemList
					});

					for (var sets = $Temp.getItemSets(), i = sets.length; i--;) {
						$Temp.setActiveCats(sets[i]);
					}

					$Temp.setPanelsView({
						objects: 'show'
					});
				} else {
					_class.showError('NO_ITEMS');
				}
			}
		}
	}

	function createCatList (groupCatList, singleCatList) {
		var groupCats = [],
			singleCats = [],
			itemSets = [];

		if (!groupCatList.length && !singleCatList.length) {
			return;
		}

		if ($Temp.slideMode) {
			for (var i = 0; i < groupCatList.length; i++) {
				var id = groupCatList[i],
					cat = $Temp.getCats(id);

				if ($Temp.getParentCats(id)) {
					if (setGroupCat(id, cat, 'grouplist')) {
						cat.element.addClass('bxmap-item').removeClass('bxmap-parent-item');
						groupCats.push(cat.element);
						singleCats.push($Temp.setCatSets(id));
					}
				} else {
					setChildren(id, cat.parentCat);
				}
			}

			for (var i = 0; i < singleCatList.length; i++) {
				var id = singleCatList[i];

				if ($Temp.getSingleCats(id)) {
					var cat = $Temp.getCats(id),
						parent = $Temp.getCats(cat.parentCat);

					if (setSingleCat(id, cat)) {
						$Temp.getCatSets(cat.parentCat).append(cat.element);
						itemSets.push($Temp.itemSets[id]);
					}

					if (!parent.empties) {
						parent.empties = 0;
					}

					if (cat.empty) {
						parent.empties++;
					}
				}
			}

			$Temp.getList('cats').append(groupCats);
			$Temp.getList('subcats').append(singleCats);
		} else {
			for (var j = 0; j < groupCatList.length; j++) {
				var id = groupCatList[j];

				if ($Temp.getGroupCats(id)) {
					if (id != $Temp.noSubCatsName) {
						for (var chain = $Temp.getCats(id).chain, i = chain.length; i--;) {
							var cat = $Temp.getCats(chain[i]);

							if (setGroupCat(chain[i], cat, 'sublist')) {
								cat.element.addClass('bxmap-parent-item').removeClass('bxmap-item');

								if (i == chain.length - 1) {
									groupCats.push(cat.element);
								} else {
									$Temp.getCats(chain[i + 1]).list.append(cat.element);
								}
							}
						}
					}
				}
			}

			for (var i = 0; i < singleCatList.length; i++) {
				var id = singleCatList[i],
					cat = $Temp.getCats(id);

				if (setSingleCat(id, cat)) {
					if (cat.parentCat == $Temp.noSubCatsName) {
						singleCats.push(cat.element);
					} else {
						$Temp.getCats(cat.parentCat).list.append(cat.element);
					}
				}
			}

			var singles = $Temp.getList('cats').children('[data-action="list"]'),
				standard = singles.filter('[data-id="standard"]');

			if (singles.length) {
				$(singles[0]).before(groupCats);
			} else {
				$Temp.getList('cats').append(groupCats);
			}

			if (standard.length) {
				$(standard).before(singleCats);
			} else {
				$Temp.getList('cats').append(singleCats);
			}
		}

		$Temp.getList('items').append(itemSets);

		function setSingleCat (id, cat) {
			if (!$Temp.getCurrentCats(id)) {
				var realCount = $Temp.getCounts(id),
					availCount = parseInt(cat.count);

				if (realCount) {
					if (availCount && realCount >= availCount) {
						set();
					} else {
						if (cat.complete) {
							cat.count = realCount;
							set();
						} else if ($Temp.getNormalID(id, false)) {
							unset();
						}
					}
				} else {
					cat.empty = true;
					unset();
				}

				itemSets.push($Temp.setItemSets(id));
				$Temp.setFilterCat(id);
				$Temp.setCurrentCats(id, true);
				setChildren(id, cat.parentCat);
				return cat;
			}

			function set () {
				cat.complete = true;
				delete cat.empty;
				cat.element.removeClass('bxmap-uncomplete');
			}

			function unset () {
				delete cat.complete;
				cat.element.addClass('bxmap-uncomplete');
			}
		}

		function setGroupCat (id, cat, action) {
			if (cat && !$Temp.getCurrentCats(id)) {
				if (cat.list) {
					cat.list.empty();
				}

				$Temp.setCurrentCats(id, {});
				cat.element.attr('data-action', action);
				setChildren(id, cat.parentCat);
				return cat;
			}
		}

		function setChildren (id, parentID) {
			var parent = $Temp.getCats(cat.parentCat);

			if (parent) {
				if (!parent.childrenCats) {
					parent.childrenCats = [];
				}

				parent.childrenCats.push(id);
			}
		}
	}

	function createItem (id, parentID, catID, data) {
		switch (_class.get('pageType')) {
			case 'objects':
			case 'events':
				return createPoint(
					id,
					parentID,
					catID,
					data,
					$Temp.getCatIcon(catID),
					'point'
				);
			case 'routes':
				return setRoutePoints(createPoint(
					id,
					parentID,
					catID,
					data,
					$Temp.getRouteIcon(),
					'route'
				));
		}

		function setRoutePoints (object) {
			if (object) {
				$.extend(object, {
					points: [id],
					coords: [],
					polyLine: null,
					status: $Temp.defaultStatus
				});
				object.element.addClass('bxmap-parent-item');

				if (object.point) {
					object.coords.push(object.point);
				}

				if (data.points.length) {
					var sublist = $Common.createElement('*ul', 'bxmap-sublist', {
							'data-container': id
						}).appendTo(object.element);

					for (var i = 0; i < data.points.length; i++) {
						var _id = id + '_' + i,
							pointData = data.points[i];

						if (pointData.name || pointData.description) {
							var routePoint = createPoint(
								_id,
								id,
								object.cat,
								pointData,
								$Temp.getRouteIcon(),
								'routepoint'
							);

							if (routePoint && routePoint.element) {
								sublist.append(routePoint.element);
								object.points.push(_id);
								object.coords.push(routePoint.point);
							}
						} else {
							object.coords.push($Map.createPoint($Temp.getItemCoords(pointData, catID)));
						}
					}

					if (data.closed) {
						object.coords.push(object.coords[0]);
					} else {
						object.end = true;
					}

					object.polyLine = $Map.createPolyLine(
						id,
						object.coords,
						$Temp.getRouteIcon()._pathDefault,
						true
					);
				}
			}

			return object;
		}

		function createPoint (id, parentID, catID, data, icon, type) {
			var coords = $Temp.getItemCoords(data, catID),
				values = $Temp.getItemValues(data, catID),
				parentItem,
				parentName;

			if (parentID) {
				parentItem = $Temp.getItems(parentID);
				parentName = parentItem.values.name;
			}

			if (!values.name && !parentName) {
				return;
			}

			var content = setContent(id, parentItem, catID, values, coords),
				item = {
					id: id,
					parentItem: parentID,
					cat: catID,
					data: data,
					values: values,
					coords: coords,
					content: content,
					type: type
				},
				classes = [
					'bxmap-item',
					'bxmap-type-' + type
				],
				additional = _class.addItemClasses(data, values);

			if (additional) {
				switch ($Common.getType(additional)) {
					case 'String':
						classes.push(additional);
						break;
					case 'Array':
						classes = classes.concat(additional);
						break;
				}
			}

			item.element = $Common.createElement('*li', classes.join(' '), {
				'data-id': id,
				'data-item': 'list'
			});
			object = $Temp.setItems(id, item);

			if (coords) {
				$Temp.setMapBounds(coords);
				object.point = $Temp.setMarker(id, {
					name: values.name,
					coords: coords,
					catID: catID,
					icon: icon,
					status: $Temp.defaultStatus
				});
			} else {
				object.element.addClass('bxmap-nocoords');
			}

			object.element.append(content.clone(true));
			return object;
		}
	}

	function createCatItem (id, data, options) {
		var cat = $Temp.getCats(id);

		if (cat) {
			return cat;
		} else if (!data.name) {
			return;
		}

		var name = $Common.createElement('*span', 'bxmap-cat-name', {
				'data-item': 'name'
			}).append(
				$Common.createElement('*span', 'bxmap-cat-title').html(data.name)
			),
			params = {
				title: data.name,
				'data-id': id,
				'data-action': options.children ? '' : 'list'
			},
			classes = [
				'bxmap-item',
				id
			],
			htmlCount = $Common.createElement('*span', 'bxmap-cat-count', {
				'data-value': 'count'
			});

		cat = $Temp.setCats(id, $.extend(options, {
			id: id,
			element: $Common.createElement(
				'*li',
				classes.join(' '),
				params
			).append(name.append(htmlCount)),
			name: data.name,
			htmlCount: htmlCount
		}));

		if (cat.children && !$Temp.slideMode) {
			cat.list = $Common.createElement('*ul', 'bxmap-cat-sublist', {
				'data-category': 'container'
			}).appendTo(cat.element);
		}

		if (!_class.get('noCatIcons')) {
			var rule = '';

			cat.selectors = [
				'.' + id + '.bxmap-item:before',
				'.' + id + ' .bxmap-item:before',
				'.bxmap-list-scroll .bxmap-list-item.' + id + ':before'
			];

			if (data.icon) {
				rule += 'background-image:url(' + data.icon + ');';
			}

			if (cat.pos) {
				rule += 'background-position:-' + cat.pos + 'px 0;';
			}

			if (rule) {
				$Common.createRule(cat.selectors.join(','), rule);
			}
		}

		return cat;
	}

	function setContent (id, parentItem, catID, values, coords) {
		var content = $Common.createElement('*div', 'bxmap-item-wrapper').append($Temp.createGeoLink()),
			info = $Common.createElement('*div', 'bxmap-item-info').prependTo(content),
			body = $Common.createElement('*div', 'bxmap-item-detail').append(
				$Temp.createRouteLink()
			),
			count = 0;

		if (parentItem) {
			var parentName = parentItem.values.name;

			if (parentName) {
				createName({
					name: parentName,
					url: $Temp.getValue(parentItem.data, 'url', parentItem.cat),
					classes: 'bxmap-item-parentname'
				}).appendTo(info);
			}
		}

		if (values.name) {
			createName({
				name: values.name,
				url: values.url
			}).appendTo(info);
		}

		if (values.address) {
			body.append(
				$Common.createElement('*div', 'bxmap-item-address').html(values.address)
			);
			count ++;
		}

		if (values.description) {
			body.append(
				$Common.createElement('*div', 'bxmap-item-description').html(values.description)
			);
			count ++;
		}

		if (values.opening) {
			var opening = $Common.createElement('*div', 'bxmap-item-opening').html(values.opening);

			switch (_class.get('pageType')) {
				case 'events':
					body.prepend(opening);
				default:
					body.append(opening);
					break;
			}

			count ++;
		}

		if (count) {
			info.append(body);
		}

		if (values.phone || values.link) {
			var contacts = $Common.createElement('*div', 'bxmap-item-contacts').appendTo(content);

			if (values.phone) {
				contacts.append($Temp.createPhones(values.phone));
			} else {
				contacts.addClass('bxmap-single');
			}

			if (values.link) {
				contacts.append($Temp.createLinks(values.link));
			}
		}

		return content;

		function createName (_data) {
			_data.classes = (_data.classes || '').split(/\s+/);
			_data.classes.push('bxmap-item-name');

			var name = $Common.createElement('*div', _data.classes.join(' '));

			if (_data.url) {
				name.append(
					$Common.createElement('a', 'bxmap-item-url', {
						href: _data.url
					}).html(_data.name)
				)
			} else {
				name.html(_data.name);
			}

			return name;
		}
	}

	function showMapObjectsInfo (data) {
		data = data || _class.getMapObjects();
		_class.trackMapObjects(data);
	}

	function createTitle (options) {
		var element = $Common.createElement('*dt', 'bxmap-section-head');

		Object.keys(options).forEach(function (i) {
			element.append(
				$Common.createElement('*span', 'bxmap-section-title bxmap-' + i, {
					title: options[i],
					'data-action': 'collapse',
					'data-type': i
				})
				.html(options[i])
			);
		});

		return element;
	}

	function processingInput (options) {
		(options.context || $(document))
			.on('keydown', options.element, _keydown)
			.on('input', options.element, _input);

		function _keydown (e) {
			var item = e.target,
				$item = $(e.target);

			if ($item.is(options.element)) {
				$item.data({
					timer: setTimeout(function () {
						if (item.value != $item.data('value')) {
							_manage(item);
						} else {
							_react(item, e.keyCode);
						}
					}, 0)
				});
			}
		}

		function _input (e) {
			clearTimeout($(e.target).data('timer'));
			_manage(e.target);
		}

		function _manage (item) {
			$(item).data({
				value: item.value
			});
			_react(item);
		}

		function _react (item, keyCode) {
			if (options.callback) {
				options.callback(item, keyCode);
			}
		}
	}

	function reactInput (item, keyCode) {
		if (keyCode) {
			if (keyCode == 27) {
				item.value = '';
				$(item).trigger('check:value');
			}
		} else {
			$(item).trigger('check:value', {
				value: item.value
			});
		}
	}

	function hideGroupCats (ids) {
		switch ($Common.getType(ids)) {
			case 'String':
				ids = $Common.getArray(ids);
			case 'Array':
				break;
			case 'Object':
				ids = Object.keys(ids);
				break;
			default:
				ids = $Temp.getActiveGroupCats();
				break;
		}

		for (var i = ids.length; i--;) {
			var id = ids[i];

			if ($Temp.getActiveGroupCats(id)) {
				hideActiveObjects($Temp.getCurrentCats(id));
				$Temp.resetActiveGroupCats(id);
			}
		}

		if ($Temp.slideMode && !$Temp.getActiveGroupCats().length) {
			$Temp.getContainer().removeClass('bxmap-toggle-subcats bxmap-toggle-objects bxmap-slide-collapse');
		}
	}

	function showGroupCats (ids) {
		switch ($Common.getType(ids)) {
			case 'String':
				ids = $Common.getArray(ids);
			case 'Array':
				break;
			case 'Object':
				ids = Object.keys(ids);
				break;
			default:
				ids = $Temp.getGroupCats();
				break;
		}

		$Temp.setActiveGroupCats(ids, $Temp.standardMode);
		$Temp.setRule('subcats');
		$Temp.getContainer().addClass('bxmap-toggle-subcats');
	}

	function hideActiveObjects (ids) {
		var params = {},
			cats,
			parentStatus,
			item,
			oldItems = $Temp.getFilter('items', 'visible'),
			info;

		switch ($Common.getType(ids)) {
			case 'String':
				ids = $Common.getArray(ids);
			case 'Array':
				break;
			case 'Object':
				ids = Object.keys(ids);
				break;
			default:
				ids = $Temp.getActiveCats();
				break;
		}

		for (var i = ids.length; i--;) {
			if ($Temp.getActiveCats(ids[i])) {
				hideCat(ids[i]);
			}
		}

		cats = $Temp.getActiveCats();
		item = $Temp.getItems($Temp.getActiveID());

		if (item && ids.indexOf(item.cat) >= 0) {
			$Temp.toggleActiveItem();
			params.item = null;
		}

		if (cats.length) {
			params.cat = cats.join(',');
		} else {
			params.cat = null;
			$Temp.setPanelsView({
				objects: 'invisible'
			});
		}

		$Common.replaceState({
			query: params
		});
		manageView({
			items: oldItems
		});
		info = _class.getMapObjects();
		showMapObjectsInfo(info);
		return info;

		function hideCat (id) {
			if (!$Temp.noCatsMode) {
				$Temp.getItemSets(id).removeClass('bxmap-active');
			}

			$Temp.resetActiveCats(id, $Temp.standardMode);
			$Temp.resetVisibleItems({
				cat: id
			});

			switch (_class.get('pageType')) {
				case 'objects':
				case 'events':
					$Temp.resetVisibleMarkers({
						cat: id
					});
					break;
				case 'routes':
					$Temp.resetVisibleRoutes({
						cat: id
					});
					break;
			}
		}
	}

	function showActiveObjects (query, mode, callback) {
		var oldItems = $Temp.getFilter('items', 'visible'),
			params = {};

		if ($Temp.noCatsMode) {
			switch (_class.get('pageType')) {
				case 'objects':
				case 'events':
					$Temp.setVisibleMarkers({
						list: $Temp.getFilterCounts()
					});
					break;
				case 'routes':
					$Temp.setVisibleRoutes({
						list: $Temp.getFilterCounts()
					});
					break;
			}

			if (mode) {
				oldItems = [];
			}

			showCats($Temp.getSingleCats());
			update();
			$Common.replaceState({
				query: {
					cat: null
				}
			});
		} else {
			if (query.cat && query.cat.length) {
				showCats(query.cat);
				update();
				$Common.replaceState({
					query: {
						cat: query.cat.join(',')
					}
				});
			} else {
				done();
			}
		}

		function showCats (cats) {
			for (var i = cats.length; i--;) {
				var id = cats[i],
					cat = $Temp.getCats(id);

				if (cat && $Temp.getSingleCats(id)) {
					showCat(id, cat);
				} else {
					cats.splice(i, 1);
				}
			}

			function showCat (id, cat) {
				if (!$Temp.getActiveCats(id)) {
					var parentID = cat.parentCat;

					$Temp.setActiveCats(id);
					$Temp.getItemSets(id).addClass('bxmap-active');
					$Temp.setVisibleItems({
						cat: id
					});
					items = $Temp.getFilterCounts(id);

					if ($Temp.slideMode) {
						if (!$Temp.getActiveGroupCats(parentID)) {
							showGroupCats(parentID);
						}
					} else {
						$Temp.setActiveGroupCats(cat.chain.slice(1), $Temp.standardMode);
					}

					switch (_class.get('pageType')) {
						case 'objects':
						case 'events':
							$Temp.setVisibleMarkers({
								list: items
							});
							break;
						case 'routes':
							$Temp.setVisibleRoutes({
								list: items
							});
							break;
					}
				}
			}
		}

		function update () {
			var id;

			if (query.item) {
				id = query.item[query.item.length - 1];

				if (!$Temp.getActiveID(id)) {
					switch (_class.get('pageType')) {
						case 'objects':
						case 'events':
							_class.getWrapper().one('cluster:done', _done);
							break;
						case 'routes':
							_class.getWrapper().one('manage:done', _done);
							break;
					}
				} else {
					done();
				}
			} else {
				done();
			}

			manageView({
				items: oldItems,
				mode: mode,
				id: id
			});

			function _done () {
				$Temp.toggleActiveItem(id);
				done();
			}
		}

		function done () {
			var info = _class.getMapObjects();

			showMapObjectsInfo(info);

			if (callback) {
				callback(info);
			}
		}
	}

	function toggleCluster (cluster) {
		$Map.setClusterView(cluster);
	}

	function manageView (options) {
		var newItems = $Temp.getFilter('items', 'visible');

		if ($Temp.slideMode) {
			if (!newItems.length) {
				$Temp.getContainer().removeClass('bxmap-toggle-objects');
			} else if (!$Temp.noCatsMode) {
				$Temp.getContainer().addClass('bxmap-toggle-objects');

				if (options.mode) {
					$Temp.getContainer().addClass('bxmap-slide-collapse');
				}
			}

			$Temp.setRule('subcats,objects');
		} else {
			$Temp.toggleClearButton();

			if (!newItems.length) {
				$Temp.getLayer('objects').addClass('bxmap-none');
			} else {
				$Temp.getLayer('objects').removeClass('bxmap-none');

				if ($Temp.getLayer('objects').hasClass('bxmap-show')) {
					$Temp.setRule('objects');
				} else {
					$Temp.setPanelsView({
						objects: 'collapse'
					});
				}
			}
		}

		if (options.items.length != newItems.length) {
			switch (_class.get('pageType')) {
				case 'objects':
				case 'events':
					updateMarkers();
					break;
				case 'routes':
					updateRoutes();
					break;
			}
		} else if (!$Temp.getActiveID(options.id)) {
			_class.getWrapper().trigger('cluster:done');
		}

		_class.getWrapper().trigger('manage:done');

		function updateMarkers () {
			if (newItems.indexOf($Temp.getActiveID()) < 0 && !theSameAddress) {
				$Balloon.close();
				resetActiveMarker(true);
			}

			if (!$Temp.slideMode || $Temp.overlayMode) {
				$Map.updateMarkerCluster($Temp.getVisibleMarkers());
			} else {
				clusterDone = false;
			}
		}

		function updateRoutes () {
			if (newItems.indexOf($Temp.getActiveRoute()) < 0) {
				$Balloon.close();
				$Temp.setActiveRoute();
			}

			$Map.updateRoutes($Temp.getVisibleRoutes());
		}
	}

	function setActiveMarker (id, catID, item, emulation) {
		$Temp.setActiveItem(id);

		if (emulation) {
			$Map.getMarkerInCluster(id, {
				marker: $Temp.getMarker(id)
			}).then(function (cluster, markers, bounds) {
				$Temp.resetActiveIDs();

				if (markers) {
					toggleCluster(cluster);
					createMultipleBalloon(markers, bounds);
				} else {
					createSingleBalloon();
				}
			});
		} else {
			$Temp.getItems(id).element.get(0).scrollIntoView(true);
			createSingleBalloon();
		}

		$Common.replaceState({
			query: {
				item: id
			}
		});

		function createSingleBalloon () {
			$Temp.setActiveID(id);
			$Map.setMarkerView(
				$Temp.getMarker(id),
				$Temp.getCatIcon(catID),
				catID,
				$Temp.activeStatus
			);
			$Balloon.show({
				id: id,
				item: item,
				marker: $Temp.getMarker(id)
			});
			_class.trackItem({
				itemID: id,
				item: item
			});
			showMapObjectsInfo();
		}

		function createMultipleBalloon (markers, bounds) {
			$Balloon.show({
				id: id,
				markers: markers,
				bounds: bounds
			});
		}
	}

	function resetActiveMarker (deletePoint) {
		if ($Temp.getActiveID() && $Temp.getActiveItem()) {
			if (!_class.get('pageType', 'routes')) {
				var catID = $Temp.getItems($Temp.getActiveID()).cat;

				$Map.setMarkerView(
					$Temp.getMarker($Temp.getActiveID()),
					$Temp.getCatIcon(catID),
					catID,
					$Temp.defaultStatus
				);

				if (!theSameAddress && $Temp.getActiveID($Temp.getActiveItem().id)) {
					$Temp.setActiveItem();
				}
			}

			if (deletePoint) {
				$Temp.setActiveItem();
				$Temp.setActiveID();
			}

			$Common.replaceState({
				query: {
					item: null
				}
			});
		}
	}

	function setActiveRouteMarker (id, routeID, item, status) {
		$Temp.setActiveItem(id);
		$Temp.setActiveID(id);
		$Map.setMarkerView(
			$Temp.getMarker(id),
			$Temp.getRouteIcon(),
			'route',
			status
		);
		$Balloon.show({
			id: id,
			item: item,
			marker: $Temp.getMarker(id)
		});
		$Common.replaceState({
			query: {
				item: id
			}
		});
	}

	function resetActiveRouteMarker (status) {
		if ($Temp.getActiveID()) {
			$Map.setMarkerView(
				$Temp.getMarker($Temp.getActiveID()),
				$Temp.getRouteIcon(),
				'route',
				status || $Temp.defaultStatus
			);
			$Temp.setActiveItem();
			$Temp.setActiveID();
			$Balloon.close();
		}
	}

	function setActiveRoute (routeID, pointID) {
		var id = pointID || routeID,
			active = $Temp.getActiveRoute();

		if (active) {
			$Map.setRouteView(active);
		}

		$Temp.setActiveRoute(routeID);
		$Temp.setActiveID(id);
		$Temp.setActiveItem(id);
		$Map.setRouteView(routeID, $Temp.activeStatus);
		$Common.replaceState({
			query: {
				item: routeID
			}
		});
	}

	function resetActiveRoute () {
		if ($Temp.getActiveRoute()) {
			$Map.setRouteView($Temp.getActiveRoute());
			resetActiveRouteMarker();
			$Temp.setActiveRoute();
			$Temp.setActiveItem();
			$Temp.setActiveID();
			$Common.replaceState({
				query: {
					item: null
				}
			});
		}
	}

	function createBalloon () {
		if ($Balloon) {
			return;
		} else {
			$Balloon = _createBalloon();
		}

		function _createBalloon () {
			var titleHeight = parseInt($('[data-container="title"]', _class.getWrapper()).css('height'));

			if ($Temp.slideMode) {
				var $list = $Common.createElement('*div', 'bxmap-multilist-container', {
						'data-container': 'multilist'
					}).append(
						$Common.createElement('*div', 'bxmap-multilist-title', {
							'data-action': 'toggle',
							'data-type': 'multilist'
						}).append(
							$Common.createElement('*span', 'bxmap-multilist-count', {
								'data-item': 'count'
							}),
							$Common.createElement('*span', 'bxmap-multilist-show').text(_class.get('interfaceText').showObjects),
							$Common.createElement('*span', 'bxmap-multilist-close').text(_class.get('interfaceText').showCurrentObject)
						),
						$Temp.setScroll('few', $Temp.createScroll({
							classes: 'bxmap-single-scroll bxmap-list-scroll'
						}))
					),
					$image = $Common.createElement('*div', 'bxmap-popup-image', {
						'data-container': 'image'
					}),
					$container = $Common.createElement('*div', 'bxmap-section bxmap-popup bxmap-' + _class.get('mapType'), {
						'data-container': 'popup'
					}).append(
						createTitle({
							show: _class.get('interfaceText').popupTitle,
							close: _class.get('interfaceText').closeList
						}),
						$list,
						$Common.createElement('*dd', 'bxmap-section-body', {
							'data-target': 'collapse',
							'data-container': 'select'
						}).append(
							$Temp.setScroll('popup', $Temp.createScroll({
								classes: 'bxmap-data-container',
								params: {
									'data-container': 'info'
								},
								content: [
									$image,
									$Common.createElement('*div', 'bxmap-popup-container').append(
										$Common.createElement('*div', 'bxmap-popup-inner', {
											'data-container': 'content'
										})
									)
								]
							}))
						)
					),
					list = {
						count: $('[data-item~="count"]', $list),
						scroll: $('[data-wrapper~="scroll"]', $list)
					},
					info = {
						content: $('[data-container~="content"]', $container),
						scroll: $('[data-container~="info"]', $container)
					};

				$Temp.getContainer().append($container);

				simpleBalloon.prototype.build = function () {
					var _self = this;

					this.scrollContent_ = $('[data-content~="scroll"]', $list);

					$container.on('click', '[data-action~="collapse"][data-type~="close"]', function () {
						_self.close();
						toggleCluster();
						resetActiveMarker(true);
					});

					_class.getWrapper()
						.on('click', '[data-action~="content"]', function () {
							_self.selectList_($(this).data('id'));
							$list.toggleClass('bxmap-active');
						})
						.on('cluster:done', function (e, clusterLength) {
							if (theSameAddress) {
								_self.updateList(clusterLength);
							}
						})
						.on('cluster:detected', function (e, markers) {
							if (markers) {
								_self.show({
									markers: markers
								});
							} else {
								_self.close();
								$Temp.setActiveID();
							}
						});
				};

				simpleBalloon.prototype.show = function (options) {
					var _self = this;

					options = options || {};

					if (options.markers) {
						var id = options.id || options.markers[0].id;

						resetActiveMarker();
						theSameAddress = true;
						$container.addClass('bxmap-multiple');
						this.scrollContent_.empty();
						this.updateList_({
							add: options.markers
						});
						this.selectList_(id);
						showPopup(options.markers[0], options.markers.length);
					} else if (options.marker) {
						theSameAddress = false;
						$container.removeClass('bxmap-multiple');
						this.setBalloonContent(options.id);
						showPopup(options.marker);
					}

					function showPopup (marker, length) {
						$container.addClass('bxmap-active bxmap-visible');
						setBalloonStatus(true);
						$Map.panToMarker(marker);
					}
				};

				simpleBalloon.prototype.close = function () {
					if (getBalloonStatus()) {
						$container.removeClass('bxmap-active bxmap-visible');
						closeBalloon();
					}
				};

				simpleBalloon.prototype.setBalloonContent = function (id) {
					var result = _class.createItemContent(getItemContent(id));

					$image.empty();

					if (result.photo) {
						var _img = $Common.createElement('img', {
							src: result.photo
						});

						if (!_img.get(0).complete) {
							_img.bind('load', function () {
								$(this).appendTo($image);
								setTimeout(function () {
									$Temp.setRule('popup');
								}, 0);
							});
						} else {
							_img.appendTo($image);
						}
					}

					info.content.empty().append(result.content);
					setTimeout(function () {
						$Temp.setRule('popup');
					}, 0);
				};

				simpleBalloon.prototype.updateList = function (clusterLength) {
					var _self = this;

					if (clusterLength) {
						$Map.getMarkerInCluster($Temp.activeIDs, {
							noZoom: true
						}).then(function (cluster, markers) {
							$Temp.resetActiveIDs();

							if (markers) {
								var _items = [],
									active = 0;

								Object.keys(markers).forEach(function (i) {
									var _item = _self.createListItem_(markers[i].id);

									if ($Temp.getActiveID(markers[i].id)) {
										active = $Temp.getActiveID();
										_item.addClass('bxmap-active');
									}

									_items.push(_item);
								});

								_self.scrollContent_.empty().append(_items);

								if (!active) {
									_self.selectList_(_items[0].data('id'));
								} else {
									$Temp.setRule('few');
								}

								toggleCluster(cluster);
							} else {
								toggleCluster();
								_self.close();
							}
						});
					}
				};

				simpleBalloon.prototype.createListItem_ = function (id) {
					$Temp.activeIDs[id] = createListItem(id);
					return $Temp.activeIDs[id];
				};

				simpleBalloon.prototype.updateList_ = function (data) {
					if (data.add) {
						list.count.html(data.add.length);

						for (var i = data.add.length; i--;) {
							this.scrollContent_.append(this.createListItem_(data.add[i].id));
						}
					}
				};

				simpleBalloon.prototype.selectList_ = function (id) {
					selectList.call(this, id);
				};

				return new simpleBalloon();
			} else {
				var _template = $Common.createElement('*div', 'bxmap-popup bxmap-popup-float bxmap-' + _class.get('mapType')).append(
						$Common.createElement('*span', 'bxmap-popup-close', {
							title: _class.get('interfaceText').closeList,
							'data-action': 'close',
							'data-type': 'popup'
						}),
						$Common.createElement('*div', 'bxmap-popup-list', {
							'data-container': 'collapse list'
						}).append(
							$Temp.setScroll('few', $Temp.createScroll({
								classes: 'bxmap-single-scroll bxmap-list-scroll'
							}))
						),
						$Common.createElement('*div', 'bxmap-data-container', {
							'data-container': 'info'
						}).append(
							$Common.createElement('*div', 'bxmap-popup-image', {
								'data-container': 'image'
							}),
							$Common.createElement('*div', 'bxmap-popup-container').append(
								$Common.createElement('*div', 'bxmap-popup-inner', {
									'data-container': 'content'
								})
							)
						)
					),
					_sizer = $Common.createElement('*div', 'bxmap-sizer bxmap-popup');

				if (_class.get('mapType', 'google')) {
					googleBalloon.prototype.extend = function (a, b) {
						return (function (object) {
							for (var property in object.prototype) {
								this.prototype[property] = object.prototype[property];
							}

							return this;
						}).apply(a, [b]);
					};

					googleBalloon.prototype.buildDom_ = function () {
						var _self = this;

						this.container_ = _template;
						this.sizer_ = _sizer;
						this.image_ = $('[data-container~="image"]', this.container_);
						this.content_ = $('[data-container~="content"]', this.container_);
						this.list_ = $('[data-container~="list"]', this.container_);
						this.scroll_ = $('[data-wrapper~="scroll"]', this.list_);
						this.scrollContent_ = $('[data-content~="scroll"]', this.list_);

						google.maps.event.addDomListener($('[data-action~="close"]', this.container_).get(0), 'click', function () {
							_self.close();
							toggleCluster();
							resetActiveMarker(true);
							google.maps.event.trigger(_self, 'closeclick');
						});
						google.maps.event.addDomListener(this.list_.get(0), 'mouseenter', function () {
							_class.getMap().setOptions({
								scrollwheel: false,
								draggable: false
							});
						});
						google.maps.event.addDomListener(this.list_.get(0), 'mouseleave', function () {
							_class.getMap().setOptions({
								scrollwheel: true,
								draggable: true
							});
						});

						_class.getWrapper()
							.on('click', '[data-action~="content"]', function () {
								_self.selectList_($(this).data('id'));
							})
							.on('cluster:done', function (e, clusterLength) {
								if (theSameAddress) {
									_self.updateList(clusterLength);
								}
							})
							.on('cluster:detected', function (e, markers) {
								$Temp.resetActiveIDs();

								if (markers) {
									_self.show({
										markers: markers
									});
								} else {
									_self.close();
									$Temp.setActiveID();
								}
							});
					};

					googleBalloon.prototype.onAdd = function () {
						var panes = this.getPanes();

						if (panes) {
							this.container_.appendTo(panes.floatPane);

							if (theSameAddress) {
								restoreScroll.call(this);
							}
						}
					};

					googleBalloon.prototype.show = function (options) {
						var _self = this;

						options = options || {};
						$Temp.resetActiveIDs();

						if (options.markers) {
							var id = options.id || options.markers[0].id;

							resetActiveMarker();
							theSameAddress = true;
							this.container_.addClass('bxmap-multiple');
							this.scrollContent_.empty();
							this.updateList_({
								add: options.markers
							});
							this.selectList_(id);
							showPopup(options.markers[0], options.markers.length);
						} else if (options.marker) {
							theSameAddress = false;
							this.container_.removeClass('bxmap-multiple');
							this.setBalloonContent(options.id);
							showPopup(options.marker);
						}

						function showPopup (marker, length) {
							_self.delta = length ? $Temp.getIcon().anchor[1] : marker.getIcon().anchor.y;
							_self.set('anchor', marker);
							_self.bindTo('anchorPoint', marker);
							_self.bindTo('position', marker);
							_self.setMap(_class.getMap());
							_self.container_.addClass('bxmap-active');
							setBalloonStatus(true);
						}
					};

					googleBalloon.prototype.draw = function () {
						var _self = this,
							projection = this.getProjection();

						if (projection) {
							var latLng = this.get('position');

							if (latLng) {
								var pos = projection.fromLatLngToDivPixel(latLng),
									height = this.container_.get(0).clientHeight;

								this.container_.css({
									top: pos.y - height - (this.delta || 0) + 'px',
									left: pos.x + 'px'
								});
							} else {
								this.close();
							}
						}
					};

					googleBalloon.prototype.redraw_ = function () {
						var height = this.get('minHeight') || 0,
							maxHeight = Math.min(_class.get('height'), this.get('maxHeight') || 0),
							content = this.get('content');

						if (content) {
							var contentSize = this.getElementSize_(content, maxHeight);

							if (height < contentSize.height) {
								height = contentSize.height;
							}
						}

						if (maxHeight) {
							height = Math.min(height, maxHeight);
						}

						height = Math.min(height, _class.get('height'));
						this.content_.height(height);

						if (theSameAddress) {
							restoreScroll.call(this);
						}

						this.draw();
						this.container_.addClass('bxmap-visible');
						setTimeout(this.panToView.bind(this), 0);
					};

					googleBalloon.prototype.onRemove = function () {

					};

					googleBalloon.prototype.close = function () {
						if (getBalloonStatus()) {
							this.container_.remove();
							closeBalloon();
						}
					};

					googleBalloon.prototype.updateList = function (clusterLength) {
						var _self = this;

						if (clusterLength) {
							$Map.getMarkerInCluster($Temp.activeIDs, {
								noZoom: true
							}).then(function (cluster, markers) {
								$Temp.resetActiveIDs();

								if (markers) {
									var _items = [],
										active = 0;

									Object.keys(markers).forEach(function (i) {
										var _item = _self.createListItem_(markers[i].id);

										if ($Temp.getActiveID(markers[i].id)) {
											active = $Temp.getActiveID();
											_item.addClass('bxmap-active');
										}

										_items.push(_item);
									});

									_self.scrollContent_.empty().append(_items);

									if (!active) {
										_self.selectList_(_items[0].data('id'));
									} else {
										restoreScroll.call(_self);
									}

									toggleCluster(cluster);
								} else {
									toggleCluster();
									_self.close();
								}
							});
						}
					};

					googleBalloon.prototype.createListItem_ = function (id) {
						$Temp.activeIDs[id] = createListItem(id);
						return $Temp.activeIDs[id];
					};

					googleBalloon.prototype.updateList_ = function (data) {
						var _self = this;

						if (data.add) {
							Object.keys(data.add).forEach(function (i) {
								_self.scrollContent_.append(_self.createListItem_(data.add[i].id));
							});
						}
					};

					googleBalloon.prototype.selectList_ = function (id) {
						selectList.call(this, id);
					};

					googleBalloon.prototype.setBalloonContent = function (id) {
						var _self = this,
							result = _class.createItemContent(getItemContent(id));

						this.container_.addClass('bxmap-noimage').removeClass('bxmap-visible');
						this.set('content', result.content);
						this.content_.empty().append(this.get('content'));
						this.image_.empty();
						this.redraw_();
						google.maps.event.trigger(_self, 'domready');

						if (result.photo) {
							var image = $Common.createElement('img', {
								src: result.photo
							});

							if (image.get(0).complete) {
								insert();
							} else {
								image.bind('load', insert);
							}
						}

						function insert () {
							_self.image_.append(image);
							_self.container_.removeClass('bxmap-noimage');
							_self.redraw_();
						}
					};

					googleBalloon.prototype.position_changed = function () {
						//this.draw();
					};

					googleBalloon.prototype.panToView = function () {
						var _self = this,
							projection;

						if (this.container_.length) {
							projection = this.getProjection();

							if (projection) {
								set();
							} else {
								_class.getWrapper().one('map:idle', function () {
									setTimeout(function () {
										projection = _self.getProjection();
										if (projection) {
											set();
										}
									}, 0);
								});
							}
						}

						function set () {
							var markerPos = projection.fromLatLngToContainerPixel(_self.get('position')),
								centerPos = projection.fromLatLngToContainerPixel(_class.getMap().getCenter()),
								width = _self.container_.get(0).offsetWidth,
								height = _self.container_.get(0).offsetHeight,
								bounds = _class.getMap().getBounds(),
								posLeftBottom = projection.fromLatLngToContainerPixel(bounds.getSouthWest()),
								posRightTop = projection.fromLatLngToContainerPixel(bounds.getNorthEast()),
								delta = $Temp.overlayMode ? titleHeight : 0,
								corners = {
									top: markerPos.y - height - 60,
									right: markerPos.x + width / 2 + 20,
									bottom: markerPos.y + 20,
									left: markerPos.x - width / 2 - 20
								},
								offset = {
									top: corners.top - posRightTop.y - delta,
									right: posRightTop.x - corners.right,
									bottom: posLeftBottom.y - corners.bottom,
									left: corners.left - posLeftBottom.x
								},
								status;

							if (offset.bottom < 0) {
								centerPos.y -= offset.bottom;
								status = true;
							} else if (offset.top < 0) {
								centerPos.y += Math.min(offset.top, offset.bottom);
								status = true;
							}

							if (offset.right < 0) {
								centerPos.x -= offset.right;
								status = true;
							}

							if (offset.left < 0) {
								centerPos.x += offset.left;
								status = true;
							}

							if (status) {
								_class.getMap().panTo(projection.fromContainerPixelToLatLng(centerPos));
							}
						}
					};

					googleBalloon.prototype.getElementSize_ = function (element, opt_maxHeight) {
						var sizer = this.sizer_.clone(true);

						if (typeof element == 'string') {
							sizer.html(element);
						} else {
							sizer.append(element.clone(true));
						}

						$(document.body).append(sizer);
						var size = new google.maps.Size(sizer.width(), sizer.height());

						sizer.remove();
						return size;
					};

					return new googleBalloon();
				} else if (_class.get('mapType', 'yandex')) {
					var _self,
						delta = 0,
						count = 0;

					_template.find('[data-container~="image"]').html('$[myHeaderContent]');
					_template.find('[data-container~="content"]').html('$[myBodyContent]');
					_template.find('[data-content~="scroll"]').html('$[myListContent]');

					_class.getWrapper()
						.on('cluster:done', function (e, clusterLength) {
							if (theSameAddress && getBalloonStatus()) {
								if (clusterLength) {
									$Map.getMarkerInCluster($Temp.activeIDs, {
										noZoom: true
									}).then(function (cluster, markers) {
										if (markers) {
											var list = $Common.createElement('*div'),
												children,
												active;

											toggleCluster(cluster);
											$Temp.activeIDs = {};

											Object.keys(markers).forEach(function (i) {
												$Temp.activeIDs[markers[i].id] = true;
												list.append(createListItem(markers[i].id));
											});

											children = list.children();

											if ($Temp.getActiveIDs($Temp.getActiveID())) {
												active = children.filter('[data-id~="' + $Temp.getActiveID() + '"]').addClass('bxmap-active');
											} else {
												active = children.eq(0).addClass('bxmap-active');
												$Temp.setActiveID(active.data('id'));
												_self.setBalloonContent($Temp.getActiveID());
											}

											_self.updateList(list);
										} else {
											reset();
										}
									});
								} else {
									reset();
								}
							}

							function reset () {
								toggleCluster();
								$Balloon.close();
								$Temp.resetActiveIDs();
								$Temp.setActiveID();
							}
						})
						.on('cluster:detected', function (e, markers, bounds, id) {
							$Temp.resetActiveIDs();

							if (markers) {
								$Balloon.show({
									id: id || markers[0].id,
									markers: markers,
									bounds: bounds
								});
							} else {
								$Balloon.close();
							}
						})
						.on('click', '[data-action~="content"]', function () {
							if (theSameAddress) {
								_self.selectList_($(this).data('id'));
							}
						});

					yandexBalloon.prototype.layout = ymaps.templateLayoutFactory.createClass(
						_template.get(0).outerHTML,
						{
							build: function () {
								_self = this;

								this.constructor.superclass.build.call(this);
								this.container_ = $('.bxmap-popup').addClass('bxmap-active bxmap-noimage bxmap-visible');
								this.image_ = $('[data-container~="image"]', this.container_);
								this.content_ = $('[data-container~="content"]', this.container_);
								this.list_ = $('[data-container~="list"]', this.container_);
								this.scroll_ = $('[data-wrapper~="scroll"]', this.list_);
								this.scrollContent_ = $('[data-content~="scroll"]', this.list_);

								$('[data-action~="close"]', this.container_).one('click', $.proxy(this.onCloseClick, this));
								this.checkLoad();

								if (theSameAddress) {
									this.container_.addClass('bxmap-multiple');
								}

								_class.getWrapper().trigger('balloon:build');
								restoreScroll.call(this);
							},

							setBalloonPosition: function () {
								this.container_.css({
									left: 0,
									top: delta - this.container_.get(0).offsetHeight + 'px'
								});
								this.events.fire('shapechange');
							},

							checkLoad: function () {
								var _self = this,
									image = this.image_.find('img');

								this.setBalloonPosition();

								if (image.get(0) && !image.get(0).complete) {
									image.on('load', set);
								} else {
									set();
								}

								function set () {
									_self.container_.removeClass('bxmap-noimage');
									_self.setBalloonPosition();
									$Temp.setRule('few');
								}
							},

							selectList_: function (id) {
								selectList.call(this, id);
							},

							setBalloonContent: function (id) {
								var result = _class.createItemContent(getItemContent(id));

								this.container_.addClass('bxmap-noimage');
								this.content_.empty().append(result.content);
								this.image_.empty();

								if (result.photo) {
									this.image_.append($Common.createElement('img', {
										src: result.photo
									}));
								}

								this.checkLoad();
								$Temp.setRule('few');
							},

							updateList: function (data) {
								this.scrollContent_.empty().append(data);
								$Temp.setRule('few');
							},

							clear: function () {
								this.constructor.superclass.clear.call(this);
							},

							onCloseClick: function (e) {
								toggleCluster();
								closeBalloon();
								resetActiveMarker(true);
								this.events.fire('userclose');
							},

							getShape: function () {
								if (this.container_.parent().length) {
									var position = this.container_.position(),
										delta = $Temp.overlayMode ? titleHeight : 0;

									return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
										[
											position.left,
											position.top - delta
										],
										[
											position.left + this.container_.get(0).offsetWidth,
											position.top + this.container_.get(0).offsetHeight - delta
										]
									]));
								}
							}
						}
					);

					yandexBalloon.prototype.show = function (settings) {
						var options = {},
							properties = {
								shadow: false,
								layout: this.layout,
								autoPanCheckZoomRange: true
							},
							coords;

						settings = settings || {};
						$Temp.resetActiveIDs();

						if (settings.markers) {
							var list = $Common.createElement('*div');

							Object.keys(settings.markers).forEach(function (i) {
								$Temp.activeIDs[settings.markers[i].id] = true;
								list.append(createListItem(settings.markers[i].id));
							});

							resetActiveMarker();
							list.children().filter('[data-id~="' + settings.id + '"]').addClass('bxmap-active');
							$Temp.setActiveID(settings.id);
							theSameAddress = true;
							_class.getWrapper().one('balloon:build', function (e) {
								e.stopPropagation();
								_self.selectList_(settings.id);
							});
							delta = - _class.get('cluster').set[0].size / 2;
							coords = settings.markers[0].geometry.getCoordinates();
							$.extend(options, {
								myListContent: list.get(0).innerHTML
							});
						} else if (settings.marker) {
							var result = _class.createItemContent(getItemContent(settings.marker.id));

							theSameAddress = false;
							delta = ymaps.option.presetStorage.get(settings.marker.options.get('preset')).iconImageOffset[1];
							coords = settings.marker.geometry.getCoordinates();
							$.extend(options, {
								myHeaderContent: result.photo ? '<img src="' + result.photo + '">' : '',
								myBodyContent: result.content.get(0).outerHTML
							});
						}

						setBalloonStatus(true);
						_class.getMap().balloon.open(
							coords,
							options,
							properties
						);
					};

					yandexBalloon.prototype.close = function () {
						if (getBalloonStatus()) {
							closeBalloon();
							_class.getMap().balloon.close();
						}
					};

					return new yandexBalloon();
				}
			}
		}

		function closeBalloon () {
			setBalloonStatus();

			switch (_class.get('pageType')) {
				case 'objects':
				case 'events':
					$Common.replaceState({
						query: {
							item: null
						}
					});
					$Temp.resetActiveIDs();
					break;
				case 'routes':
					if ($Temp.getActiveID()) {
						resetActiveRoute();
					}

					break;
			}
		}

		function setBalloonStatus (status) {
			balloonIsOpen = !!status;
		}

		function getBalloonStatus () {
			return balloonIsOpen;
		}

		function createListItem (id) {
			var item = $Temp.getItems(id);

			if (item) {
				item.element.addClass('bxmap-active');
			}

			return $Common.createElement('*li', 'bxmap-list-item ' + item.cat, {
				'data-id': id,
				'data-catID': item.cat,
				'data-action': 'content'
			}).append(
				$Common.createElement('*span', 'bxmap-list-name').append(
					$Common.createElement('*span', 'bxmap-list-title').html(item.values.name)
				)
			);
		}

		function getItemContent (id) {
			var item = $Temp.getItems(id);

			return {
				itemID: id,
				catID: item.cat,
				name: item.values.name,
				point: item.point,
				content: item.content,
				data: item.data,
				geo: $Temp.createGeoLink(),
				route: $Temp.createRouteLink(),
				correspondence: $Temp.getCorrespondence(item.cat),
				fields: $Temp.getFields(item.cat)
			};
		}

		function restoreScroll () {
			$Temp.restoreScroll('few', this.scroll_);
			$Temp.setRule('few');
		}

		function selectList (id) {
			if (!$Temp.getActiveID(id)) {
				this.scrollContent_.find('[data-id="' + $Temp.getActiveID() + '"]').removeClass('bxmap-active');
				$Temp.setActiveID(id);
				$Temp.setActiveItem(id);
				this.scrollContent_.find('[data-id="' + id + '"]').addClass('bxmap-active');
			}

			this.setBalloonContent(id);
			_class.trackItem({
				itemID: id,
				item: $Temp.getItems(id),
				group: Object.keys($Temp.getActiveIDs())
			});
			showMapObjectsInfo();
			$Common.replaceState({
				query: {
					item: $Temp.getActiveID()
				}
			});

			if ($Temp.directionMode) {
				$Temp.getLayer('direction').trigger('set:direction');
			}
		}

		function googleBalloon () {
			this.extend(googleBalloon, google.maps.OverlayView);
			this.buildDom_();
		}

		function yandexBalloon () {}

		function simpleBalloon () {
			this.build();
		}
	}
})({
	require: {
		pageType: 'PAGETYPE_NOT_DEFINED',
		mapBounds: 'MAP_PARAMETERS_NOT_DEFINED'
	},
	dependence: {},
	independence: {
		items: {
			ajax: 'AJAX_NOT_DEFINED'
		}
	}
},
{
	height: 550,
	replaceRules: true,
	narrowWidth: 1000,
	extraNarrowWidth: 800,
	animationTime: 200,
	icon: {
		objects: {
			url: 'objects.png',
			size: [30, 40],
			anchor: [15, 37],
			logo: [30, 30]
		},
		events: {
			url: 'events.png',
			size: [30, 30],
			anchor: [15, 15],
			logo: [30, 30]
		},
		routes: {
			url: 'routes.png',
			size: [30, 40],
			anchor: [15, 37],
			logo: [30, 30]
		},
		direction: {
			url: 'direction.png',
			size: [30, 40],
			anchor: [15, 37]
		}
	},
	path: {
		def: {
			size: [20, 20],
			anchor: [10, 10],
			offset: [60, 30]
		},
		active: {
			size: [20, 20],
			anchor: [10, 10],
			offset: [80, 30]
		},
		strokeWeight: 4,
		strokeColor: '#4f84b0',
		strokeOpacity: 0.7,
		strokeColorActive: '#ec473b',
		strokeOpacityHover: 1
	},
	directionOptions: {
		def: {
			size: [20, 20],
			anchor: [10, 10],
			offset: [80, 0]
		},
		strokeWeight: 4,
		strokeColor: '#0080ff',
		strokeOpacity: 1
	}
});