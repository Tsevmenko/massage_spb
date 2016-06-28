//'use strict';

var $GeoMapp = function ($Data, alternate, date) {
	var $,
		moduleReady,
		moduleComplete,
		mapReady,
		mapComplete,
		$Module = function () {
			var $wrapper,
				$component,
				params = {
					require: {
						defaultPath: 'UNKNOWN_DEFAULT_PATH',
						device: 'DEVICE_NOT_DEFINED'
					}
				},
				initOptions = {},
				initStart,
				timers = {},
				errorList = {},
				callback = '$GeoMapp.temporary_mapScriptLoaded',
				_class = {
					temporary_mapScriptLoaded: function (status) {
						$Map.mapInitialize(status).then(function () {
							delete _class.temporary_mapScriptLoaded;
							mapReady = true;
							_class.getWrapper().trigger('map:ready');
						});
					},

					init: function (options) {
						if (initStart || document.documentMode < 9) {
							return;
						}

						document.write('<div id="bxMapFakeElement"></div>');

						if (!window.$) {
							loadLibrary();
						} else {
							var versions = window.$.fn.jquery.split('.'),
								major = parseInt(versions[0]),
								minor = parseInt(versions[1]);

							if (major < 2 && minor < 9) {
								loadLibrary();
							} else {
								$ = window.$;
								next();
							}
						}

						function loadLibrary () {
							if (options.defaultPath) {
								$Common.loadScript({
									address: options.defaultPath.libs + 'jquery.js',
									id: 'jquery',
									errorName: 'COMMON_SCRIPT_NOT_LOADED',
									listener: 'listenMainScriptLoading',
									listenerErrorName: 'ERRORS_COMMON_LOADING'
								}, function (error) {
									if (error) {
										var fake = document.getElementById('bxMapFakeElement');

										if (fake) {
											fake.parentNode.removeChild(fake);
										}
									} else {
										$ = jQuery.noConflict(true);
										next();
									}
								});
							} else {
								_class.showError('UNKNOWN_DEFAULT_PATH');
							}
						}

						function next () {
							_class.$ = $;
							initStart = true;
							$.extend(initOptions, options);

							for (var list = Object.keys(params.require), i = list.length; i--;) {
								if (initOptions[list[i]]) {
									if (list[i] == 'device') {
										$Data.deviceType = Object.keys(initOptions.device)[0];
									} else {
										$Data[list[i]] = initOptions[list[i]];
									}
								} else {
									setErrorOutput(params.require[list[i]]);
									return;
								}
							}

							$Temp.run();
							$Common.loadScript({
								address: [initOptions.device[_class.get('deviceType')]],
								id: 'common',
								errorName: 'COMMON_SCRIPT_NOT_LOADED',
								listener: 'listenMainScriptLoading',
								listenerErrorName: 'ERRORS_COMMON_LOADING'
							}, function (error) {
								if (error) {
									_class.showError(error);
								} else {
									if (checkRequiredList()) {
										return;
									}

									$Common.loadScript({
										address: _class.get('libs'),
										id: 'lib',
										errorName: 'COMMON_SCRIPT_NOT_LOADED',
										listener: 'listenMainScriptLoading',
										listenerErrorName: 'ERRORS_COMMON_LOADING'
									}, function (error) {
										if (error) {
											_class.showError(error);
										} else {
											moduleReady = true;
											_class.getWrapper()
												.one('module:complete', function () {
													moduleComplete = true;
												})
												.one('map:ready', function () {
													setTimeout(function () {
														prepareDone();
													}, 0);
												})
												.one('map:complete', function () {
													mapComplete = true;
												})
												.trigger('module:ready', ['GeoMapp']);

											$(function () {
												if ($Data.withoutMap) {
													prepareDone();
												} else {
													var address = _class.get('mapScript')[_class.get('mapType')];

													switch ($Common.getType(address)) {
														case 'Array':
															address = address[0];
															break;
														case 'Object':
															address = address.main;
													}

													if (address) {
														_action(address);
													} else {
														_error('ERRORS_MAP_LOADING');
													}
												}

												function _action (address) {
													var name = $Map.init({
															wrapper: _class.getWrapper(),
															status: status,
															mapType: _class.get('mapType'),
															pageType: _class.get('pageType')
														}),
														param = {};

													param[name] = callback;
													$Common.loadScript({
														address: $Common.getURL({
															url: address,
															replace: param
														}),
														id: 'maps',
														errorName: 'MAP_SCRIPT_NOT_LOADED',
														listener: 'listenMapScriptLoading',
														listenerErrorName: 'ERRORS_MAP_LOADING',
														timeout: true
													}, function (error) {
														if (error) {
															_error(error);
														}
													});
												}

												function _error (error) {
													if (_class.get('device', 'mobile')) {
														$Data.withoutMap = true;
														prepareDone();
													} else {
														_class.showError(error);
													}
												}
											});
										}
									});
								}

								function checkRequiredList () {
									var checkList = [
											params.require
										],
										dependence = Object.keys(params.dependence),
										independence = Object.keys(params.independence);

									for (var i = dependence.length; i--;) {
										if (dependence[i] in initOptions) {
											checkList.push(params.dependence[dependence[i]]);
										}
									}

									for (var i = independence.length; i--;) {
										if (!(independence[i] in initOptions)) {
											checkList.push(params.independence[independence[i]]);
										}
									}

									for (var i = checkList.length; i--;) {
										Object.keys(checkList[i]).forEach(function (j) {
											if (j in initOptions) {
												$Data[j] = initOptions[j];
												delete errorList[checkList[i][j]];
											} else {
												errorList[checkList[i][j]] = true;
											}
										});
									}

									if (Object.keys(errorList).length) {
										setErrorOutput(errorList);
									} else {
										clearTimeout(timers.required);
										$Common.extend(initOptions);

										var type = _class.get('routeType');

										if (!type) {
											$Data.routeType = {};
										}

										type = $Data.routeType[_class.get('mapType')];

										if (!type || !type.length || !type[0]) {
											$Data.routeType[_class.get('mapType')] = ['driving'];
										}
									}

									return Object.keys(errorList).length;
								}
							});
						}

						function setErrorOutput (message) {
							timers.required = setTimeout(function () {
								_class.showError(message);
							}, 0);
						}
					},

					install: function (component, registrate, start) {
						$Temp.temporary_start =  start;

						if (registrate && $Common.getType(registrate, 'function')) {
							var result = registrate.call(this, initOptions);

							if (result.options) {
								$Common.extend(result.options);
							}

							if (result.requires) {
								$.extend(params, result.requires);
							}
						}

						extendModule(component);
						$(window).on('unload', function (e) {
							$GeoMapp = null;
							$Module = null;
							$Data = null;
							$Common = null;
							$Map = null;
							$Params = null;
							$Temp = null;
						});
						delete this.install;

						return this;
					},

					extend: function (component) {
						$Temp.manage({
							name: moduleReady,
							event: 'module:ready',
							callback: function () {
								extendModule(component);
							}
						});
						return this;
					},

					ready: function (callback) {
						$Temp.manage({
							name: moduleReady,
							event: 'module:ready',
							callback: function () {
								callback.call(_class, $);
							}
						});
						return this;
					},

					complete: function (callback) {
						$Temp.manage({
							callback: function () {
								callback.call(_class, $);
							}
						});
						return this;
					},

					mapReady: function (callback) {
						$Temp.manage({
							name: mapReady,
							event: 'map:ready',
							callback: function () {
								callback.call(_class);
							}
						});
						return this;
					},

					mapComplete: function (callback) {
						$Temp.manage({
							name: mapComplete,
							event: 'map:complete',
							callback: function () {
								callback.call(_class, _class.getMap());
							}
						});
						return this;
					},

					removeError: function () {
						if (Object.keys(errorList).length) {
							errorList = {};
							this.getWrapper().removeClass('bxmap-error').data('errorContainer').empty();
						}

						return this;
					},

					showError: function (messageList) {
						var barHeight = initOptions.barHeight || _class.get('barHeight');

						if ($) {
							$(function () {
								var _wrapper = _class.getWrapper(),
									errorContainer = _wrapper.data('errorContainer'),
									_list = $Common.createElement('*ul', 'bxmap-group', {
										'data-target': 'message'
									}),
									list;

								if (!errorContainer) {
									var errorContainer = $Common.createElement('*div', 'bxmap-error-message', {
										'data-container': 'message'
									}).appendTo(
										$Common.createElement('*div', 'bxmap-error-container', {
											'data-container': 'list'
										}).appendTo(_wrapper)
									)

									_wrapper.data({
										errorContainer: errorContainer
									});
								}

								_list.appendTo(errorContainer);
								_wrapper
									.addClass('bxmap-show-wrapper bxmap-error')
									.removeClass('bxmap-loading');

								if (barHeight) {
									_wrapper.css({
										top: barHeight + 'px'
									});
								}

								if (messageList) {
									switch ($Common.getType(messageList)) {
										case 'String':
											list = [messageList];
											break;
										case 'Array':
											list = messageList;
											break;
										case 'Object':
											list = Object.keys(messageList);
											break;
										default:
											return;
									}
								} else {
									_list.empty();
									list = Object.keys(errorList);
								}

								for (var i = 0; i < list.length; i++) {
									if (messageList && !messageList[list[i]] && list[i] in errorList) {
										errorList[list[i]].remove();
									} else {
										errorList[list[i]] = $Common.createElement('*li', 'bxmap-group-item').append(
											$Common.createElement('*span', 'bxmap-block bxmap-fail').html(
												_class.get('parseMessages')[list[i]] ||
												_class.get('routeMessages')[list[i]] ||
												list[i]
											)
										).appendTo(_list);
									}
								}
							});
						} else {
							document.addEventListener('DOMContentLoaded', function () {
								var _wrapper = document.getElementById('bxMapContainer'),
									_container = document.createElement('bxmap'),
									errorContainer = document.createElement('bxmap'),
									_list = document.createElement('bxmap'),
									list = [messageList];

								if (!_wrapper) {
									_wrapper = document.getElementById('bxMapFakeElement');
									_wrapper.id = 'bxMapContainer';
								}

								if (barHeight) {
									_wrapper.style.top = barHeight + 'px';
								}

								_wrapper.className = _wrapper.className.replace(/\s*bxmap-loading\b/ig, '');
								_wrapper.className += ' bxmap-show-wrapper bxmap-error';
								_container.className = 'alt-block bxmap-error-container';
								errorContainer.className = 'alt-block bxmap-error-message';
								_list.className = 'alt-unordered-list bxmap-group';
								_wrapper.appendChild(_container);
								_container.appendChild(errorContainer);
								errorContainer.appendChild(_list);

								for (var i = 0; i < list.length; i++) {
									var item = document.createElement('bxmap'),
										error = document.createElement('bxmap');

									item.className = 'alt-list-item bxmap-group-item';
									error.className = 'alt-inline bxmap-block bxmap-fail';
									error.innerHTML = _class.get('parseMessages')[list[i]] || _class.get('routeMessages')[list[i]] || list[i];
									item.appendChild(error);
									_list.appendChild(item);
								}
							});
						}

						return this;
					},

					getWrapper: function (status) {
						if (!$wrapper || !$wrapper.length) {
							$wrapper = $('#bxMapContainer');

							if ($wrapper.length) {
								$('#bxMapFakeElement').remove();
							} else {
								$wrapper = $Common.createElement('*div', 'bxmap-wrapper', {
									id: 'bxMapContainer'
								}).replaceAll($('#bxMapFakeElement'));
							}

							$wrapper.addClass([
								'bxmap-map-' + (initOptions.mapType || _class.get('mapType')),
								'bxmap-page-' + (initOptions.pageType || _class.get('pageType'))
							].join(' '));
						}

						return $wrapper;
					},

					getMap: function () {
						return $Map.Map;
					},

					hide: function () {
						$(document.documentElement).removeClass('bxmap-root-overflow');
						this.getWrapper().addClass('bxmap-none');
						return this;
					},

					show: function () {
						$(document.documentElement).addClass('bxmap-root-overflow');
						this.getWrapper().removeClass('bxmap-none');
						return this;
					},

					getMapObjects: function () {
						var count = $Temp.getFilter('count');

						return {
							cats: {
								visible: $Temp.getFilter('cats', 'visible'),
								filtered: count ? $Temp.getFilter('cats', 'singleFiltered') : []
							},
							items: {
								visible: $Temp.getFilter('items', 'visible'),
								filtered: count ? $Temp.getFilter('items', 'filtered') : [],
								active: $Temp.getActiveID()
							}
						};
					},

					trackItem: function () {
						
					},

					trackDirection: function () {
						
					},

					trackMapObjects: function () {
						
					},

					getRequestURL: function (url, params) {
						return $Common.getURL({
							url: url,
							replace: params
						});
					},

					getRemoteData: function (options) {
						var defer = $.Deferred();

						if (options && (options.cat || options.item)) {
							send();
						} else {
							defer.resolve();
						}

						return defer.promise();

						function send () {
							options.type = _class.get('pageType');
							$.ajax({
								url: _class.getRequestURL(_class.get('ajax'), options),
								type: options.method || 'get',
								dataType: options.dataType || 'json'
							}).done(function (data) {
								defer.resolve(data);
							}).fail(function () {
								defer.resolve();
							});
						}
					},

					get: function (name, value) {
						if (name) {
							switch (name) {
								case 'cats':
								case 'items':
									if (value === undefined) {
										return $Data[name];
									} else {
										if ($Data[name]) {
											return $Data[name][value];
										} else {
											return;
										}
									}
								case '$Common':
									return $Common;
								case '$Map':
									return $Map;
								case '$Params':
									return $Params;
								case '$Temp':
									return $Temp;
								default:
									if ($Data[name] === undefined) {
										return;
									} else {
										if (value) {
											switch ($Common.getType(value)) {
												case 'Array':
													for (var i = 0; i < value.length; i++) {
														var result = _get(name, value[i]);

														if (result) {
															return result;
														}
													}

													return;
												case 'Object':
													for (var list = Object.keys(value), i = 0; i < list.length; i++) {
														var result = _get(name, list[i]);

														if (result) {
															return result;
														}
													}

													return;
												default:
													var result = _get(name, value);

													if (result) {
														return result;
													}

													return;
											}
										} else {
											return $Data[name];
										}
									}
							}
						} else {
							return Object.keys($Data);
						}

						function _get (name, value) {
							switch ($Common.getType($Data[name])) {
								case 'Object':
									if (value in $Data[name]) {
										return $Data[name][value];
									}
								case 'Array':
									return $Data[name].indexOf(value) >= 0;
								default:
									return $Data[name] === value;
							}
						}
					},

					set: function (name, value) {
						if (name) {
							switch (name) {
								case 'cats':
								case 'items':
								case 'fields':
									if (value) {
										if (!$Data[name]) {
											$Data[name] = {};
										}

										for (var keys = Object.keys(value), i = keys.length; i--;) {
											if (value[keys[i]]) {
												$Data[name][keys[i]] = value[keys[i]];
											} else {
												delete $Data[name][keys[i]];
											}
										}
									} else {
										delete $Data[name];
									}

									return;
								case 'animationTime':
								case 'verticalTime':
								case 'horizontalTime':
								case 'geolocation':
								case 'mapBounds':
									if (value !== undefined) {
										$Data[name] = value;
										return $Data[name];
									} else {
										delete $Data[name];
										return;
									}
							}
						}
					}
				};

			return _class;

			function extendModule (component) {
				if (component && $Common.getType(component, 'object')) {
					for (var list = Object.keys(component), i = list.length; i--;) {
						_class[list[i]] = component[list[i]];
					}
				}
			}

			function prepareDone () {
				var pageType = _class.get('pageType');

				if (pageType) {
					var icons = _class.get('icon'),
						icon = icons[pageType],
						cluster = _class.get('cluster');

					if (!icon.anchor) {
						icon.anchor = [
							icon.size[0] / 2,
							icon.size[1]
						];
					}

					Object.keys(icons).forEach(function (type) {
						icons[type].url = $Temp.getFullPath(icons[type].url, 'images');
					});

					if (cluster.icon) {
						cluster.icon = $Temp.getFullPath(cluster.icon, 'images');
					}

					Object.keys(cluster.set).forEach(function (size) {
						if (cluster.set[size].icon) {
							cluster.set[size].icon = $Temp.getFullPath(cluster.set[size].icon, 'images');
						}
					});

					if (!$Data.withoutMap) {
						$Temp.setDirectionIcon($Map.createRouteIconSet(
							icons.direction,
							_class.get('directionOptions'),
							'direction'
						));
						$Temp.setRouteIcon($Map.createRouteIconSet(
							icons.routes,
							_class.get('path'),
							'route'
						));
					}

					switch (pageType) {
						case 'objects':
						case 'events':
							$Map.setClusterOptions(
								cluster,
								$Common.createElement('*div', 'bxmap-cluster bxmap-cluster-' + _class.get('mapType'), {
									'data-item': 'cluster'
								})
							);
							break;
					}
				}

				if ($Temp.temporary_start) {
					if ($Common.getType($Temp.temporary_start, 'function')) {
						$Temp.temporary_start.call(_class);
					}

					delete $Temp.temporary_start;
				}

				$(document).on('click', '[data-bxmap-action]', function (e) {
					e.preventDefault();

					var _self = $(this),
						action = _self.data('bxmap-action'),
						query = _self.data('bxmap-filter'),
						cat = _self.data('bxmap-cat'),
						item = _self.data('bxmap-item'),
						filter = null,
						add = {};

					switch (action) {
						case 'geo':
							add.type = 'geo';
							break;
						case 'show':
							add.screen = 'full';
							break;
					}

					if (query) {
						filter = {
							query: query,
							mode: _self.data('bxmap-mode')
						};
					}

					switch (action) {
						case 'geo':
						case 'show':
							if (cat) {
								add.cat = cat;
							}

							if (item) {
								add.item = item;
							}

							if (cat || item) {
								$Temp.parseData({
									query: $Common.getArrayQuery({
										url: _self.data('bxmap-query'),
										add: add
									}),
									title: _self.data('bxmap-title'),
									filter: filter
								}, $Temp.checkCounts);
							} else {
								$Module.toggleOverlayMode(true);
							}

							break;
						case 'filter':
							_class.filterMapObjects(filter);
							break;
						case 'toggle':
							_class.toggleOverlayMode();
							break;
						default:
							_class.toggleOverlayMode(action);
							break;
					}
				});

				_class.getWrapper().on('click keydown keyup keypress input propertychange scroll wheel mousewheel focus blur change reset submit objectMarker:hover eventMarker:hover routeMarker:hover objectMarker:out eventMarker:out routeMarker:out objectMarker:click eventMarker:click routeMarker:click objectItem:click eventItem:click routeItem:click route:hover route:out route:click route:show direction:show set:direction check:value set:view cluster:detected cluster:done set:scroll module:ready module:complete map:ready map:complete balloon:build rule:done', function (e) {
					e.stopPropagation();
				});

				delete _class.init;
			}
		}(),
		$Temp = function () {
			var _class = {
				callbackStack: [],
				noCatsName: 'standard',
				noSubCatsName: '_standard',
				defaultStatus: 'default',
				activeStatus: 'active',
				names: [
					'name',
					'description',
					'photo',
					'address',
					'phone',
					'link',
					'url',
					'opening'
				],
				icons: {
					direction: {},
					route: {},
					cat: {}
				},
				filter: {
					fields: {},
					items: {
						visible: {},
						filtered: {},
						unfiltered: {}
					},
					cats: {
						visible: {},
						singleFiltered: {},
						filtered: {},
						unfiltered: {}
					}
				},
				types: {
					exclude: '!*=',
					unequal: '!=',
					include: '*=',
					equal: '='
				},
				cats: {},
				catsOrderList: {
					all: [],
					group: [],
					single: []
				},
				counts: {},
				catCounts: {},
				filterCounts: {},
				catSets: {},
				itemSets: {},
				items: {},
				routes: {},
				markers: {},
				currentItems: {},
				currentCats: {},
				visibleCats: {},
				visibleItems: {},
				visibleRoutes: {},
				visibleMarkers: {},
				activeIDs: {},
				mapBounds: {
					lat: [],
					lng: []
				},
				unloadedCats: {},
				unloadedItems: {},
				correspondence: {},
				fields: {},
				groupCats: {},
				parentCats: {},
				singleCats: {},
				activeGroupCats: {},
				lists: {},

				manage: function (options) {
					if (options && options.callback) {
						options.name = options.name || moduleComplete;
						options.event = options.event || 'module:complete';

						if ($) {
							react();
						} else {
							this.callbackStack.push(react);
						}
					}

					function react () {
						if (options.name) {
							options.callback();
						} else {
							$Module.getWrapper().one(options.event, options.callback);
						}
					}
				},

				run: function (callback) {
					for (var i = 0; i < this.callbackStack.length; i++) {
						this.callbackStack[i]();
					}

					this.callbackStack = [];
				},

				getList: function (id) {
					return getSingleProperty('lists', id);
				},

				getCorrespondence: function (id) {
					var cat = this.getCats(id);

					if (cat) {
						return this.correspondence[cat.fields];
					}
				},

				setCorrespondence: function (id) {
					var cat = this.getCats(id);

					if (cat) {
						var fieldID = cat.fields;

						if (fieldID && !this.correspondence[fieldID]) {
							var fields = this.getFields(id);

							if (fields) {
								this.correspondence[fieldID] = {};

								for (var list = Object.keys(fields), i = list.length; i--;) {
									if (fields[list[i]].name) {
										this.correspondence[fieldID][fields[list[i]].name] = list[i];
									}
								}

								return this.correspondence[fieldID];
							}
						}
					}
				},

				getFields: function (id) {
					var fields = $Module.get('fields');

					if (fields) {
						if (id) {
							var cat = this.getCats(id);

							if (cat) {
								return fields[cat.fields];
							}
						} else {
							return fields;
						}
					}
				},

				setFields: function (data) {
					if (data) {
						$Module.set('fields', data);
					} else {
						$Module.set('fields');
					}
				},

				getValue: function (data, name, id) {
					var cat = this.getCats(id);

					if (this.getCats(id)) {
						var correspondence = this.correspondence[this.getCats(id).fields];

						if (correspondence && data[correspondence[name]]) {
							return data[correspondence[name]];
						} else {
							return data[name];
						}
					}
				},

				getItemValues: function (data, catID) {
					var values = {};

					for (var i = 0; i < this.names.length; i++) {
						var value = this.getValue(data, this.names[i], catID);

						if (value) {
							values[this.names[i]] = value;
						}
					}

					return values;
				},

				getFullPath: function (path, type) {
					if (!/^(\/|http)/.test(path)) {
						path = ($Module.get('defaultPath')[type] + '/').replace(/\/+$/, '/') + path;
					}

					return path;
				},

				getCatID: function (id) {
					if (id) {
						return _getCatID(id) || _getItemID(id);
					}

					function _getCatID (catID) {
						if (_class.cats[catID]) {
							return catID;
						}
					}

					function _getItemID (id) {
						var item = _class.items[id];

						if (item) {
							if (item.parentItem) {
								item = _class.items[item.parentItem];

								if (!item) {
									return;
								}
							}

							if (item.cat) {
								return _getCatID(item.cat);
							}
						}
					}
				},

				getNormalID: function (id, status) {
					if (id) {
						var flag = id.indexOf(this.noCatsName) == 0;

						if (status !== undefined) {
							if (flag) {
								return !!status;
							} else {
								return !status;
							}
						} else {
							if (flag) {
								return this.noCatsName;
							} else {
								return id;
							}
						}
					} else {
						if (status !== undefined) {
							return !!status;
						} else {
							return this.noCatsName;
						}
					}
				},

				getIcon: function (type) {
					var icon = $Module.get('icon'),
						pageType = type || $Module.get('pageType');

					if (icon && pageType) {
						return icon[pageType];
					}
				},

				setCatIcon: function (id, icon) {
					id = this.getNormalID(id);

					if (icon) {
						this.icons.cat[id] = icon;
					}

					return this.icons.cat[id];
				},

				getCatIcon: function (id) {
					id = this.getNormalID(id);

					return this.icons.cat[id];
				},

				setDirectionIcon: function (icon) {
					if (icon) {
						return this.icons.direction = icon;
					}
				},

				getDirectionIcon: function () {
					return this.icons.direction;
				},

				setRouteIcon: function (icon) {
					if (icon) {
						return this.icons.route = icon;
					}
				},

				getRouteIcon: function (icon) {
					return this.icons.route;
				},

				getActiveID: function (id) {
					if (id) {
						return id == this.activeID;
					} else {
						return this.activeID;
					}
				},

				setActiveID: function (id) {
					if (id) {
						return this.activeID = id;
					} else {
						delete this.activeID;
					}
				},

				getActiveItem: function (id) {
					return this.activeItem;
				},

				setActiveItem: function (id) {
					if (this.activeItem) {
						if (Object.keys(this.getActiveIDs()).length) {
							if (!this.getActiveIDs(id)) {
								this.resetActiveIDs();
							}
						} else {
							this.activeItem.element.removeClass('bxmap-active');
						}
					}

					if (id) {
						if (this.items[id]) {
							this.items[id].element.addClass('bxmap-active');
							return this.activeItem = this.items[id];
						}
					} else {
						delete this.activeItem;
					}

					return this.activeItem;
				},

				getActiveIDs: function (id) {
					if (arguments.length) {
						return this.activeIDs[id];
					} else {
						return this.activeIDs;
					}
				},

				setActiveIDs: function (id, value) {
					if (id && value) {
						this.items[id].element.addClass('bxmap-active');
						return this.activeIDs[id] = value;
					}
				},

				resetActiveIDs: function (id) {
					if (id) {
						_reset(id);
					} else {
						for (var list = Object.keys(this.activeIDs), i = list.length; i--;) {
							_reset(list[i]);
						}
					}

					function _reset (id) {
						if (_class.items[id] && _class.items[id].element) {
							_class.items[id].element.removeClass('bxmap-active');
						}

						delete _class.activeIDs[id];
					}
				},

				getCats: function (id) {
					if (arguments.length) {
						return this.cats[id];
					} else {
						return this.cats;
					}
				},

				setCats: function (id, value) {
					if (id) {
						if (value) {
							return this.cats[id] = value;
						} else {
							delete this.cats[id];
						}
					} else {
						this.cats = {};
					}
				},

				getCatOrder: function (options) {
					if (options) {
						switch (options.type) {
							case 'group':
							case 'single':
								break;
							default:
								options.type = 'all';
								break;
						}

						if (options.id) {
							return this.catsOrderList[options.type].indexOf(options.id);
						} else {
							return this.catsOrderList[options.type];
						}
					} else {
						return this.catsOrderList.all;
					}
				},

				changeCatOrder: function (options) {
					options = options || {};

					switch (options.type) {
						case 'group':
							options.id = options.id || this.noSubCatsName;
							break;
						case 'single':
							options.id = options.id || this.noCatsName;
							break;
						default:
							options.id = options.id || this.noCatsName;
							options.type = 'all';
							break;
					}

					return change(options.id, this.catsOrderList[options.type]);

					function change (id, list) {
						var index = list.indexOf(id);

						if (index >= 0 && index != list.length - 1) {
							list.splice(index, 1);
							list.push(id);
						}

						return list;
					}
				},

				setCatOrder: function (options) {
					if (options) {
						if (options.id) {
							switch (options.type) {
								case 'group':
								case 'single':
									set(options.id, this.catsOrderList[options.type]);
									break;
							}

							set(options.id, this.catsOrderList.all);
						}
					}

					function set (id, list) {
						if (list.indexOf(id) < 0) {
							list.push(id);
						}
					}
				},

				resetCatOrder: function (options) {
					if (options) {
						if (options.id) {
							switch (options.type) {
								case 'group':
								case 'single':
									reset(options.id, this.catsOrderList[options.type]);
									break;
							}

							set(options.id, this.catsOrderList.all);
						}
					} else {
						this.catsOrderList = {
							all: [],
							group: [],
							single: []
						};
					}

					function reset (id, type) {
						var index = _class.catsOrderList[type].indexOf(id);

						if (index >= 0) {
							_class.catsOrderList[type].splice(index, 1);
							_class.catsOrderList.all.splice(_class.catsOrderList.all.indexOf(id), 1);
						}
					}
				},

				setCatSets: function (id, options) {
					if (id) {
						if (options && options.remove) {
							delete this.catSets[id];
						} else {
							if (this.catSets[id]) {
								return this.catSets[id];
							} else {
								return this.catSets[id] = this.createCatSet(id);
							}
						}
					} else {
						this.catSets = {};
					}
				},

				getCatSets: function (id) {
					return getSingleProperty('catSets', id);
				},

				getActiveCats: function (id) {
					return getProperty('visibleCats', id);
				},

				setActiveCats: function (id) {
					if (id) {
						if (this.cats[id] && this.singleCats[id] && !(id in this.visibleCats)) {
							set([id]);

							if (this.filter.cats.filtered[id]) {
								this.filter.cats.visible[id] = true;
							}
						}
					} else {
						set(Object.keys(this.cats));
					}

					function set (list) {
						for (var i = list.length; i--;) {
							var _id = list[i],
								_cat = _class.cats[_id],
								_parentID = _cat.parentCat;

							_cat.element.addClass('bxmap-active');
							_class.itemSets[_id].addClass('bxmap-active');
							_class.visibleCats[_id] = true;

							while (_parentID) {
								var _parent = _class.cats[_parentID];

								if (_parent && _class.currentCats[_parentID]) {
									if (!_class.catCounts[_parentID]) {
										_class.catCounts[_parentID] = {};
									}

									_class.catCounts[_parentID][_id] = true;
									_id = _parentID;
									_parentID = _parent.parentCat;
								} else {
									break;
								}
							}
						}
					}
				},

				resetActiveCats: function (id, mode) {
					if (id) {
						if (this.cats[id] && this.singleCats[id] && id in this.visibleCats) {
							reset([id], true);
						}
					} else {
						reset(Object.keys(this.visibleCats));
						this.catCounts = {};
					}

					function reset (list, status) {
						for (var i = list.length; i--;) {
							var _id = list[i],
								_cat = _class.cats[_id];

							_cat.element.removeClass('bxmap-active');
							_class.itemSets[_id].removeClass('bxmap-active');
							delete _class.visibleCats[_id];

							if (status) {
								var _parentID = _cat.parentCat;

								while (_parentID) {
									var _parent = _class.cats[_parentID],
										_counts = _class.catCounts[_parentID];

									if (_parent && _class.catCounts[_parentID]) {
										delete _counts[_id];

										if (Object.keys(_counts).length) {
											break;
										} else {
											/*if (mode) {
												_class.resetActiveGroupCats(_parentID);
											}*/

											_id = _parentID;
											_parentID = _parent.parentCat;
										}
									} else {
										break;
									}
								}
							}
						}
					}
				},

				getActiveSubCats: function (id) {
					if (id && _class.catCounts[id]) {
						return Object.keys(_class.catCounts[id]);
					}
				},

				getCurrentCats: function (id) {
					if (id) {
						var value = _class.currentCats[id];

						switch ($Common.getType(value)) {
							case 'Boolean':
								return value;
							case 'Object':
								return Object.keys(value);
						}
					} else {
						return Object.keys(_class.currentCats);
					}
				},

				setCurrentCats: function (id, value) {
					if (id) {
						if (value) {
							var parent = this.cats[this.cats[id].parentCat];

							this.currentCats[id] = value;

							while (parent) {
								var _cat = this.currentCats[parent.id];

								if (_cat) {
									this.currentCats[parent.id][id] = true;
									id = parent.id;
									parent = this.cats[parent.parentCat];
								} else {
									break;
								}
							}
						} else {
							var parent = this.cats[this.cats[id].parentCat];

							delete this.currentCats[id];

							while (parent) {
								var _cat = this.currentCats[parent.id];

								if (_cat) {
									delete this.currentCats[parent.id][id];
									id = parent.id;
									parent = this.cats[parent.parentCat];
								} else {
									break;
								}
							}
						}
					} else {
						return this.currentCats = {};
					}
				},

				setItemSets: function (id, options) {
					if (id) {
						if (this.itemSets[id]) {
							if (options) {
								if (options.remove) {
									this.itemSets[id].remove();
									delete this.itemSets[id];
								} else if (options.empty) {
									this.itemSets[id].empty();
								}
							}
						} else {
							this.itemSets[id] = this.createItemSet(id);
						}

						return this.itemSets[id];
					} else {
						this.getList('items').empty();
						this.itemSets = {};
					}
				},

				getItemSets: function (id) {
					return getProperty('itemSets', id);
				},

				getGroupCats: function (id) {
					return getProperty('groupCats', id);
				},

				setGroupCats: function (id) {
					setProperty('groupCats', id);
				},

				getParentCats: function (id) {
					return getProperty('parentCats', id);
				},

				setParentCats: function (id) {
					setProperty('parentCats', id);
				},

				getSingleCats: function (id) {
					return getProperty('singleCats', id);
				},

				setSingleCats: function (id) {
					setProperty('singleCats', id);
				},

				getActiveGroupCats: function (id) {
					return getProperty('activeGroupCats', id);
				},

				setActiveGroupCats: function (id, mode) {
					var list = id ? $Common.getArray(id) : this.getGroupCats();

					for (var i = list.length; i--;) {
						var id = list[i],
							cat = this.cats[id],
							counts = this.getActiveSubCats(id);

						if (!this.getActiveGroupCats(id) || (counts && counts.length)) {
							this.activeGroupCats[id] = true;
							cat.element.addClass('bxmap-active');

							if (this.getCatSets(id)) {
								this.getCatSets(id).addClass('bxmap-active');
							}

							if (cat.parentCat && mode) {
								this.setActiveGroupCats([cat.parentCat], mode);
							}
						}
					}
				},

				resetActiveGroupCats: function (id) {
					var list = id ? $Common.getArray(id) : this.getActiveGroupCats();

					for (var i = list.length; i--;) {
						var id = list[i];

						if (this.activeGroupCats[id]) {
							var counts = this.getActiveSubCats(id);

							delete this.activeGroupCats[id];
							this.cats[id].element.removeClass('bxmap-active');

							if (this.getCatSets(id)) {
								this.getCatSets(id).removeClass('bxmap-active');
							}

							if (counts) {
								this.resetActiveGroupCats(counts);
							}
						} else if (this.visibleCats[id]) {
							this.resetActiveCats(id);
						}
					}
				},

				getItems: function (id) {
					return getProperty('items', id);
				},

				setItems: function (id, value) {
					if (id) {
						if (value) {
							return this.items[id] = value;
						} else {
							delete this.items[id];
						}
					} else {
						return this.items = {};
					}
				},

				setCurrentItems: function (id) {
					if (id) {
						if (this.items[id]) {
							this.currentItems[id] = true;
							this.setCounts(this.items[id].cat, id);
						}
					} else {
						this.currentItems = {};
						this.setCounts();
					}
				},

				getCurrentItems: function (id) {
					return getProperty('currentItems', id);
				},

				setCounts: function (catID, id) {
					if (catID) {
						if (id) {
							if (!this.counts[catID]) {
								this.counts[catID] = {};
							}

							this.counts[catID][id] = true;
						} else {
							delete this.counts[catID];
						}
					} else {
						this.counts = {};
					}
				},

				getCounts: function (id, name) {
					if (id) {
						if (this.counts[id]) {
							var temp = this.counts[id];
							for (var pkey in temp) {
								if (this.items[pkey]!=undefined && this.items[pkey].coords == false)
									delete temp[pkey];
							}
							switch (name) {
								case 'items':
									return temp;
								default:
									return Object.keys(temp).length;
							}
						}
					} else {
						return this.counts;
					}
				},

				setVisibleItems: function (options) {
					if (options) {
						if (options.cat) {
							if (this.counts[options.cat]) {
								set(Object.keys(this.counts[options.cat]));
							}
						} else if (options.item) {
							set([options.item]);
						} else if (options.list) {
							set(options.list);
						}
					}

					function set (list) {
						for (var i = list.length; i--;) {
							_class.visibleItems[list[i]] = true;
						}
					}
				},

				resetVisibleItems: function (options) {
					if (options) {
						if (options.cat) {
							if (this.counts[options.cat]) {
								reset(Object.keys(this.counts[options.cat]));
							}
						} else if (options.item) {
							reset([options.item]);
						} else if (options.list) {
							reset(options.list);
						} else {
							this.visibleItems = {};
						}
					} else {
						this.visibleItems = {};
					}

					function reset (list) {
						for (var i = list.length; i--;) {
							delete _class.visibleItems[list[i]];
						}
					}
				},

				getVisibleItems: function (id) {
					return getProperty('visibleItems', id);
				},

				setCountText: function (id, value) {
					var cat = this.cats[id];

					if (cat) {
						if (!value) {
							if (this.filter.count) {
								if (cat.empty) {
									value = '?';
								} else {
									value = '';
								}
							} else {
								value = cat.count || '?';
							}
						}

						cat.htmlCount.text(value);
					}
				},

				setFilter: function (params) {
					var types = Object.keys(_class.types),
						change = 0;

					if (params) {
						if (params.mode == 'remove' || !this.filter.fields) {
							this.filter.fields = {};
						}

						if (params.query) {
							switch ($Common.getType(params.query)) {
								case 'String':
									params.query = decodeURIComponent(params.query).replace(/^\[\s*/, '').replace(/\s*\]$/, '').split('|');
								case 'Array':
									for (var i = 0; i < params.query.length; i++) {
										process(getPair(params.query[i]));
									}

									break;
								case 'Object':
									for (var list = Object.keys(params.query), i = 0; i < list.length; i++) {
										var type = list[i],
											set = params.query[type];

										for (var _list = Object.keys(set), j = 0; j < _list.length; j++) {
											var value = splitFilterValue(set[_list[j]]);

											if (value) {
												process({
													pair: {
														name: _list[j],
														value: value
													},
													type: type
												});
											}
										}
									}

									break;
							}
						}

						$Common.replaceState({
							query: {
								filter: this.getFilterQuery()
							}
						});
					}

					for (var list = Object.keys(this.filter.fields), count = 0, i = list.length; i--;) {
						var type = list[i];

						count += Object.keys(this.filter.fields[type]).length;
					}

					if (this.filters) {
						var text = '';

						if (this.filter.fields.include && this.filter.fields.include.name) {
							text = joinFilterValue(this.filter.fields.include.name);
						}

						this.setInputValue(text);
					}

					this.filter.count = count;
					this.filter.change = change;
					return {
						count: count,
						change: change
					};

					function process (options) {
						if (options) {
							remove(options.type, options.pair.name, options.pair.value);
						}

						function remove (currentType, name, values) {
							if (values.length == 1 && !values[0]) {
								delete _class.filter.fields[currentType][name];
							} else {
								types.forEach(function (type) {
									var fields = _class.filter.fields[type];

									if (type != currentType) {
										if (fields) {
											var currentValues = fields[name];

											if (currentValues) {
												values.forEach(function (value) {
													var index = currentValues.indexOf(value);

													if (index >= 0) {
														currentValues.splice(index, 1);
													}
												});

												if (!currentValues.length) {
													delete fields[name];
												}
											}
										}
									} else {
										if (fields) {
											var currentValues = fields[name];

											if (currentValues && params.mode != 'replace') {
												values.forEach(function (value) {
													if (currentValues.indexOf(value) < 0) {
														currentValues.push(value);
														change++;
													}
												});
											} else {
												fields[name] = values;
											}
										} else {
											_class.filter.fields[type] = {};
											_class.filter.fields[type][name] = values;
											change++;
										}
									}

									if (fields && !Object.keys(fields).length) {
										delete _class.filter.fields[type];
									}
								});
							}
						}
					}

					function getPair (part) {
						var parts = part.match(/^(\w+)(\!?\*?\=)(.*)$/);

						if (parts) {
							var data = {
									pair: {
										name: parts[1],
										value: splitFilterValue(parts[3])
									}
								};

							for (var i = 0; i < types.length; i++) {
								var type = types[i];

								if (parts[2] == _class.types[type]) {
									data.type = type;
									return data;
								}
							}
						}
					}
				},

				getFilter: function (name, type) {
					if (name) {
						switch (name) {
							case 'cats':
							case 'items':
								var visibleName = 'visible' + name.charAt(0).toUpperCase() + name.slice(1);

								if (this.filter.count) {
									return Object.keys(this.filter[name][type]);
								} else {
									return Object.keys(this[visibleName]);
								}
							case 'count':
							case 'change':
								return this.filter[name];
							default:
								return this.filter.fields[name];
						}
					} else {
						return this.filter;
					}
				},

				getFilterQuery: function () {
					var result = [],
						keys = Object.keys(this.types);

					for (var list = Object.keys(this.filter.fields), i = 0; i < list.length; i++) {
						var type = list[i],
							sign = this.types[type],
							params = this.filter.fields[type];

						for (var _list = Object.keys(params), j = 0; j < _list.length; j++) {
							result.push(_list[j] + sign + joinFilterValue(params[_list[j]]));
						}
					}

					return result.length ? '[' + result.join('|') + ']' : null;
				},

				resetFilterObject: function () {
					this.filterCounts = {};
					this.filter.items = {
						visible: {},
						filtered: {},
						unfiltered: {}
					};
					this.filter.cats = {
						visible: {},
						singleFiltered: {},
						filtered: {},
						unfiltered: {}
					};
				},

				setFilterCounts: function (catID, id) {
					if (catID && id) {
						if (!this.filterCounts[catID]) {
							this.filterCounts[catID] = {};
						}

						this.filterCounts[catID][id] = true;
					}
				},

				getFilterCounts: function (id) {
					if (this.filter.count) {
						if (id) {
							return this.filterCounts[id] ? Object.keys(this.filterCounts[id]) : [];
						} else {
							return this.filter.items ? Object.keys(this.filter.items.filtered) : [];
						}
					} else {
						if (id) {
							return this.counts[id] ? Object.keys(this.counts[id]) : [];
						} else {
							return Object.keys(this.currentItems);
						}
					}
				},

				getFilterItem: function (id) {
					if (this.filter.count) {
						var item = this.items[id];

						if (item) {
							var fields = Object.keys(this.filter.fields),
								count = fields.length;

							for (var i = fields.length; i--;) {
								var type = fields[i],
									field = this.filter.fields[type];

								for (var _list = Object.keys(field), j = _list.length; j--;) {
									var name = _list[j],
										values = field[name],
										value;

									if (this.names.indexOf(name) >= 0) {
										if (name in item.values) {
											value = item.values[name];
										}
									} else if (name in item.data) {
										value = item.data[name];
									} else if (name in item) {
										value = item[name];
									}

									switch (type) {
										case 'equal':
										case 'include':
											if (value) {
												for (var k = values.length; k--;) {
													if (verify(type, values[k], value)) {
														count--;
														break;
													}
												}
											} else {
												return;
											}

											break;
										case 'unequal':
										case 'exclude':
											if (value) {
												for (var k = _count = values.length; k--;) {
													if (verify(type, values[k], value)) {
														_count--;
													}
												}

												if (!_count) {
													count--;
												}
											} else {
												count--;
											}

											break;
									}
								}
							}

							return !count;
						}
					} else {
						return true;
					}

					function verify (type, value, data) {
						switch (type) {
							case 'equal':
								return value.toLowerCase() == data.toLowerCase();
							case 'unequal':
								return value.toLowerCase() != data.toLowerCase();
							case 'include':
								return data.toLowerCase().indexOf(value.toLowerCase()) >= 0;
							case 'exclude':
								return data.toLowerCase().indexOf(value.toLowerCase()) < 0;
						}
					}
				},

				filterObjects: function (options) {
					options = options || {};

					if (options.mode || options.query) {
						this.setFilter(options);
					}

					if (this.filter.count) {
						var catList = options.cats ? $Common.getArray(options.cats) : $Temp.getSingleCats();

						this.visibleMarkers = {};
						this.resetFilterObject();

						for (var i = 0; i < catList.length; i++) {
							var catID = catList[i],
								items = $Temp.getCounts(catID, 'items');

							if (items) {
								for (var itemList = Object.keys(items), j = 0; j < itemList.length; j++) {
									this.setFilterItem(itemList[j]);
								}
							}

							this.setFilterCat(catID);
						}
					} else {
						if (this.filter.items) {
							var list = Object.keys(this.filter.items.unfiltered);

							for (var i = list.length; i--;) {
								this.setFilterItem(list[i]);
							}
						}

						if (this.filter.cats) {
							var list = $Temp.getSingleCats();

							for (var i = 0; i < list.length; i++) {
								this.setFilterCat(list[i]);
							}
						}

						this.resetFilterObject();
					}
				},

				setFilterItem: function (id) {
					var item = this.items[id];

					if (item.points) {
						if (this.filter.count) {
							for (var i = 0, count = 0; i < item.points.length; i++) {
								var _id = item.points[i],
									_item = this.items[_id];

								if (this.getFilterItem(_id)) {
									_item.element.removeClass('bxmap-invisible');
									count++;
								} else {
									_item.element.addClass('bxmap-invisible');
								}
							}

							if (count) {
								this.filter.items.filtered[id] = true;
								item.element.removeClass('bxmap-invisible');
								this.setFilterCounts(item.cat, id);

								if (this.visibleCats[item.cat]) {
									this.filter.items.visible[id] = true;
									this.visibleRoutes[id] = true;
								}
							} else {
								this.filter.items.unfiltered[id] = true;
								item.element.addClass('bxmap-invisible');

								if (this.visibleCats[item.cat]) {
									delete this.filter.items.visible[id];
									delete this.visibleRoutes[id];
								}
							}
						} else {
							for (var i = 0; i < item.points.length; i++) {
								var _id = item.points[i],
									_item = this.items[_id];

								_item.element.removeClass('bxmap-invisible');
							}

							item.element.removeClass('bxmap-invisible');

							if (this.visibleCats[item.cat]) {
								this.visibleRoutes[id] = true;
							}
						}
					} else {
						if (this.filter.count) {
							if (this.getFilterItem(id)) {
								this.filter.items.filtered[id] = true;
								item.element.removeClass('bxmap-invisible');
								this.setFilterCounts(item.cat, id);

								if (this.visibleCats[item.cat]) {
									this.filter.items.visible[id] = true;

									if (this.markers[id]) {
										this.visibleMarkers[id] = this.markers[id];
									}
								}
							} else if (!this.filter.items.filtered[id]) {
								this.filter.items.unfiltered[id] = true;
								item.element.addClass('bxmap-invisible');

								if (this.visibleCats[item.cat]) {
									delete this.visibleMarkers[id];
								}
							}
						} else {
							item.element.removeClass('bxmap-invisible');

							if (this.visibleCats[item.cat] && this.markers[id]) {
								this.visibleMarkers[id] = this.markers[id];
							}
						}
					}
				},

				setFilterCat: function (id) {
					var cat = this.cats[id],
						parent = this.cats[cat.parentCat],
						items = this.getFilterCounts(id),
						count = items.length;

					this.setCountText(id, count);

					if (this.filter.count) {
						if (count) {
							this.filter.cats.filtered[id] = count;
							this.filter.cats.singleFiltered[id] = count;
							cat.element.removeClass('bxmap-invisible');

							if (this.visibleCats[id]) {
								this.filter.cats.visible[id] = true;
							}

							while (parent) {
								this.filter.cats.filtered[parent.id] = true;
								parent.element.removeClass('bxmap-invisible');
								delete this.filter.cats.unfiltered[parent.id];
								parent = this.cats[parent.parentCat];
							}
						} else if (!cat.empty) {
							this.filter.cats.unfiltered[id] = true;
							cat.element.addClass('bxmap-invisible');

							while (parent) {
								if (this.filter.cats.filtered[parent.id] || parent.empties) {
									break;
								} else {
									this.filter.cats.unfiltered[parent.id] = true;
									parent.element.addClass('bxmap-invisible');
									parent = this.cats[parent.parentCat];
								}
							}
						}
					} else {
						if (this.filter.cats.unfiltered[id]) {
							cat.element.removeClass('bxmap-invisible');

							while (parent) {
								if (this.filter.cats.filtered[parent.id]) {
									break;
								} else {
									parent.element.removeClass('bxmap-invisible');
									parent = this.cats[parent.parentCat];
								}
							}
						}
					}
				},

				toggleActiveItem: function (id) {
					id = id || this.getActiveID();

					if (id) {
						$Module.getWrapper().trigger($Module.get('pageType').slice(0, -1) + 'Marker:click', [id, true]);
					}
				},

				getMapBounds: function () {
					if (this.mapBounds.lat.length && this.mapBounds.lng.length) {
						return this.mapBounds;
					}
				},

				setMapBounds: function (options) {
					if (options) {
						this.mapBounds.lat.push(options.lat);
						this.mapBounds.lng.push(options.lng);
					} else {
						this.mapBounds.lat = [];
						this.mapBounds.lng = [];
					}
				},

				getMarker: function (id) {
					return getSingleProperty('markers', id);
				},

				setMarker: function (id, options) {
					var mapObject = $Map.createMarker(id, options);

					this.markers[id] = mapObject.marker;
					return mapObject.point;
				},

				getVisibleMarkers: function (id) {
					return getSingleProperty('visibleMarkers', id);
				},

				setVisibleMarkers: function (options) {
					if (options) {
						if (options.item) {
							set([options.item]);
						} else if (options.cat) {
							if (this.counts[options.cat]) {
								set(Object.keys(this.counts[options.cat]));
							}
						} else if (options.list) {
							set($Common.getArray(options.list));
						}
					} else {
						set(Object.keys(this.visibleItems));
					}

					function set (list) {
						for (var i = list.length; i--;) {
							var id = list[i];

							_class.filter.items.visible[id] = true;

							if (_class.markers[id]) {
								_class.visibleMarkers[id] = _class.markers[id];
							}
						}
					}
				},

				resetVisibleMarkers: function (options) {
					if (options) {
						if (options.id) {
							reset([options.id]);
						} else if (options.cat) {
							if (this.counts[options.cat]) {
								reset(Object.keys(this.counts[options.cat]));
							}
						} else if (options.list) {
							reset(Object.keys(options.list));
						}
					} else {
						this.visibleMarkers = {};
					}

					function reset (list) {
						for (var i = list.length; i--;) {
							var id = list[i];

							delete _class.filter.items.visible[id];
							delete _class.visibleMarkers[id];
						}
					}
				},

				getVisibleRoutes: function (id) {
					return getSingleProperty('visibleRoutes', id);
				},

				setVisibleRoutes: function (options) {
					if (options) {
						if (options.cat) {
							if (this.counts[options.cat]) {
								set(Object.keys(this.counts[options.cat]));
							}
						} else if (options.item) {
							set([options.item]);
						} else if (options.list) {
							set(options.list);
						}
					} else {
						set(Object.keys(this.visibleItems));
					}

					function set (list) {
						for (var i = list.length; i--;) {
							var id = list[i];

							_class.filter.items.visible[id] = true;
							_class.visibleRoutes[id] = true;
						}
					}
				},

				resetVisibleRoutes: function (options) {
					if (options) {
						if (options.cat) {
							if (this.counts[options.cat]) {
								reset(Object.keys(this.counts[options.cat]));
							}
						} else if (options.item) {
							reset([options.item]);
						} else if (options.list) {
							reset(options.list);
						}
					}

					function reset (list) {
						for (var i = list.length; i--;) {
							var id = list[i];

							delete _class.filter.items.visible[id];
							delete _class.visibleRoutes[id];
						}
					}
				},

				getActiveRoute: function (id) {
					if (id) {
						return id == this.activeRoute;
					} else {
						return this.activeRoute;
					}
				},

				setActiveRoute: function (id) {
					if (id) {
						this.activeRoute = id;
					} else {
						delete this.activeRoute;
					}
				},

				getDirection: function () {
					return this.direction;
				},

				setDirection: function (direction) {
					this.setDirectionView();

					if (direction) {
						this.direction = direction;
						this.setDirectionView(true);
					} else {
						delete this.direction;
					}
				},

				setDirectionView: function (status) {
					if (this.direction) {
						if (status) {
							$Map.addDirection(this.direction);
						} else {
							$Map.removeDirection(this.direction);
						}
					}
				},

				parseData: function (data, callback) {
					var query,
						isFilterUsed = 0,
						isFilterChanged = 0;

					switch (data.mode) {
						case 'reselect':
						case 'replace':
							query = $Common.getArrayQuery({
								replace: data.query
							});
							break;
						default:
							query = $Common.getArrayQuery({
								add: data.query
							});
							break;
					}

					if (data.filter) {
						isFilterChanged += this.setFilter(data.filter).change;
					}

					if (query.filter) {
						for (var i = 0; i < query.filter.length; i++) {
							isFilterChanged += this.setFilter({
								query: query.filter[i]
							});
						}
					}

					isFilterUsed = this.filter.count;

					if (isFilterChanged) {
						this.filterObjects();
					}

					if (data.exist) {
						this.existMode = true;
					} else {
						switch (data.exist) {
							case undefined:
								break;
							default:
								delete this.existMode;
								break;
						}
					}

					if (this.create.before) {
						this.create.before(data);
					}

					this.nonEmptyCatsList = {};
					createModuleContent({
						cats: data.cats,
						items: data.items,
						fields: data.fields,
						catID: data.catID
					}).then(function () {
						_class.loadObjects({
							mode: data.mode,
							query: query
						}).then(function (result, queryParams) {
							createModuleContent(result, true).then(function () {
								if (callback) {
									callback(queryParams);
								}
							});
						});
					});

					function createModuleContent (data, status) {
						var createDefer = $.Deferred();

						if (!data) {
							createDefer.resolve();
						} else {
							if (data.fields) {
								_class.setFields(data.fields);
							}

							data.cats = data.cats || {};
							data.items = data.items || {};
							parseCats(data.cats, function () {
								parseItems(data.items, data.catID, function () {
									data = null;
									createDefer.resolve();
								});
							});
						}

						return createDefer.promise();

						function parseCats (cats, callback) {
							var keys = Object.keys(cats);

							checkCats();

							function checkCats () {
								if (keys.length) {
									var id = keys.shift(),
										cat = _class.getCats(id),
										catData = cats[id];

									if (cat) {
										cats[id] = true;

										if (!cat.children) {
											done(id, catData);
										} else {
											checkCats();
										}
									} else {
										if (catData.parent) {
											_class.setCatOrder({
												id: id,
												type: 'group'
											});
											checkCats();
										} else {
											done(id, catData);
										}
									}
								} else {
									callback();
								}
							}

							function done (id, catData) {
								loadCatChain(id, null, function (id, cat) {
									if (catData) {
										if (catData.complete) {
											cat.complete = true;
										}

										checkObjects(id, catData.items);
									} else {
										checkCats();
									}
								});
							}

							function checkObjects (id, items) {
								if (items) {
									parseItems(items, id, checkCats);
								} else {
									checkCats();
								}
							}
						}

						function parseItems (items, catID, callback) {
							var keys = Object.keys(items),
								count = 0;

							checkItems();

							function checkItems () {
								if (count < keys.length) {
									var id = keys[count],
										object = _class.getItems(id);

									if (object) {
										done(id, {
											object: object,
											callback: checkItems
										});
									} else {
										var itemData = items[id],
											parentID = itemData.item;

										if (parentID) {
											checkParentItem(parentID, function (parent) {
												if (!parent) {
													parentID = null;
													delete itemData.item;
												} else {
													itemData.cat = parent.cat;
												}

												done(id, {
													data: itemData,
													parentID: parentID,
													callback: checkItems
												});
											});
										} else {
											done(id, {
												data: itemData,
												callback: checkItems
											});
										}
									}
								} else {
									callback();
								}
							}

							function checkParentItem (id, callback) {
								var parent = _class.getItems(id);

								if (parent) {
									callback(parent);
								} else {
									var parentData = items[id] || data.items[id];

									if (parentData) {
										var index = keys.indexOf(id);

										if (index >= 0) {
											keys.splice(keys.indexOf(id), 1);
											count--;
										}

										done(id, {
											data: parentData,
											callback: callback
										});
									} else {
										if (_class.unloadedItems[id]) {
											callback();
										} else {
											$Module.getRemoteData({
												item: id
											}).then(function (parentData) {
												if (parentData) {
													done(id, {
														data: parentData,
														callback: callback
													});
												} else {
													_class.unloadedItems[id] = true;
													callback();
												}
											});
										}
									}
								}
							}

							function done (id, params) {
								var _catID = catID,
									noItem;

								count++;

								if (!_catID) {
									if (params.object) {
										_catID = params.object.cat;
									} else if (params.data) {
										_catID = params.data.cat;
									} else {
										_catID = _class.noCatsName;
									}
								}

								if (!status || data.cats[_catID]) {
									loadCatChain(_catID, true, function (catID, cat) {
										if (!params.object) {
											params.object = _class.create.item(
												id,
												params.parentID,
												catID,
												params.data
											);
											noItem = true;
										}

										if (params.object) {
											if (params.data && params.data.pops) {
												loadCatChain('pops', true, _done);
											} else {
												_done();
											}
										} else {
											params.callback();
										}
									});
								} else {
									params.callback();
								}

								function _done (pops) {
									if (noItem) {
										if (isFilterUsed) {
											_class.setFilterItem(id);
										}
									} else {
										if (isFilterChanged) {
											_class.setFilterItem(id);
										}
									}

									addItem(id, params.object, pops);
									params.callback(params.object);
								}
							}
						}

						function loadCatChain (catID, status, callback) {
							var cat = _class.getCats(catID),
								chain = [];

							if (cat) {
								if (status && cat.children) {
									checkCat(null, catID);
								} else {
									complete(catID, cat);
								}
							} else {
								checkCat(catID);
							}

							function checkCat (id, parentID) {
								id = id || _class.noCatsName;

								var _data = getCatData(id, parentID);

								if (_data) {
									done(_data);
								} else {
									if (id && !_class.unloadedCats[id]) {
										$Module.getRemoteData({
											cat: id,
											empty: 'Y'
										}).then(function (catData) {
											if (catData && catData.cats && catData.cats[id]) {
												data.cats[id] = catData.cats[id];

												if (status && catData.cats[id].parent) {
													checkCat(null, id);
												} else {
													checkCat(id, parentID);
												}
											} else {
												_class.unloadedCats[id] = true;
												checkCat();
											}
										});
									} else {
										checkCat();
									}
								}
							}

							function getCatData (id, parentID, catData) {
								if (!id) {
									return;
								}

								var _id = parentID ? id + '_' + parentID : id;
									_data = {
										id: _id
									};

								switch (id) {
									case _class.noCatsName:
										_data.complete = true;

										if (parentID) {
											var cat = _class.getCats(_id);

											if (cat) {
												_data.object = cat;
												_data.parentCat = cat.parentCat;
											} else {
												_data.data = {
													name: $Module.get('interfaceText').groupCategoryName
												};
												_data.parentCat = parentID;
											}
										} else {
											_data.data = {
												name: $Module.get('interfaceText').groupCategoryName
											};
											_data.parentCat = _class.noSubCatsName;
										}

										break;
									case _class.noSubCatsName:
										var cat = _class.getCats(_id);

										if (cat) {
											_data.object = cat;
										} else {
											_data.complete = true;
											_data.children = true;
											_data.data = {
												name: $Module.get('interfaceText').groupCategoryName
											};
										}

										break;
									default:
										var cat = _class.getCats(_id);

										if (cat) {
											_data.object = cat;
											_data.parentCat = cat.parentCat;
											_data.children = cat.children;
										} else {
											catData = catData || data.cats[id];

											if (catData) {
												_data.data = catData;

												if (id == 'pops') {
													_data.complete = true;
													_data.parentCat = _class.noSubCatsName;
												} else {
													if (!catData.parent && !catData.cat) {
														_data.parentCat = _class.noSubCatsName;
													} else {
														_data.parentCat = catData.cat;
														_data.children = catData.parent;
													}
												}
											} else {
												_data = null;
											}
										}

										break;
								}

								return _data;
							}

							function done (_data) {
								if (_data) {
									chain.push(_data);

									if (_data.parentCat) {
										checkCat(_data.parentCat);
									} else {
										_done();
									}
								} else {
									_done();
								}

								function _done () {
									var pageType = $Module.get('pageType'),
										iconData = _class.getIcon(pageType),
										universalMarker = $Module.get('universalMarker');

									for (var chains = [], i = chain.length; i--;) {
										var _cat = chain[i],
											id = _cat.id;

										chains.unshift(id);

										if (!_cat.object) {
											var params = {
													chain: chains.slice()
												},
												fields = _cat.data.fields,
												pos = _cat.data.pos || 0,
												icon = _cat.data.icon,
												type = 'single';

											if (_cat.parentCat) {
												var parent = _class.getCats(chain[i + 1].id);

												fields = fields || parent.fields;
												pos = pos || parent.pos,
												icon = icon || parent.icon;
												params.parentCat = _cat.parentCat;
											}

											params.fields = fields || $Temp.noCatsName;
											params.pos = parseInt(pos) || 0;

											if (icon) {
												params.icon = icon;
											}

											if (_cat.data.count) {
												params.count = _cat.data.count;
											}

											if (_cat.complete) {
												params.complete = _cat.complete;
											}

											if (_cat.children) {
												params.children = _cat.children;
											}

											var cat = _class.create.cat(
													id,
													_cat.data,
													params
												);

											if (!_class.getCatIcon(id)) {
												switch (pageType) {
													case 'direction':
														pageType = 'objects';
													case 'objects':
													case 'events':
														if (!$Data.withoutMap) {
															_class.setCatIcon(id, $Map.createIconSet(
																id,
																cat,
																iconData,
																universalMarker
															));
														}
												}
											}

											if (_cat.children) {
												type = 'group';
												_class.setGroupCats(id);
											} else {
												_class.setSingleCats(id);
												_class.setParentCats(_cat.parentCat);
											}

											_class.setCatOrder({
												id: id,
												type: type
											});
										} else {
											delete _cat.object.empties;
										}
									}

									complete(chain[0].id);
								}
							}

							function complete (id, cat) {
								cat = cat || _class.getCats(id);

								var fieldsID = cat.fields;

								if (status && fieldsID && !_class.getFields(id)) {
									$Module.getRemoteData({
										field: fieldsID
									}).then(function (fieldsData) {
										if (fieldsData && fieldsData.fields) {
											_class.setFields(fieldsData.fields);
										}

										_complete();
									});
								} else {
									_complete();
								}

								function _complete () {
									_class.setCorrespondence(id);
									callback(id, cat);
								}
							}
						}

						function addItem (id, object, popular) {
							if (object) {
								if (!_class.getCurrentItems(id)) {
									_class.setCurrentItems(id);
									_class.nonEmptyCatsList[object.cat] = true;

									if (popular) {
										_class.nonEmptyCatsList.pops = true;
										_class.setItemSets('pops').append(object.element.clone(true));
										_class.setCounts('pops', id);
									}

									if (object.parentItem) {
										var parent = _class.getItems(object.parentItem);

										if (!parent.list) {
											parent.list = $Common.createElement('*ul', 'bxmap-sublist').appendTo(parent.element);
											parent.element.addClass('bxmap-parent-item');
											parent.children = true;
										}

										parent.list.append(object.element);
									} else {
										if (object.list) {
											object.list.remove();
											object.element.removeClass('bxmap-parent-item');
											delete object.children;
										}

										_class.setItemSets(object.cat).append(object.element);
									}
								}

								return object;
							}
						}
					}
				},

				loadObjects: function (options) {
					var defer = $.Deferred(),
						query = options.query,
						result = {
							cats: {},
							items: {}
						},
						queryParams = {},
						loadedItems = {};

					if (query.type && query.type.length) {
						queryParams.type = query.type[0];
					}

					if (query.screen && query.screen.length) {
						queryParams.screen = query.screen[0];
					}

					checkQueryItems(function () {
						checkQueryCats(function () {
							defer.resolve(result, queryParams);
						});
					});

					return defer.promise();

					function checkQueryItems (callback) {
						var items = $Common.getArray(query.item),
							queryObject = {
								item: {}
							};

						if (query.item) {
							queryParams.item = [query.item[query.item.length - 1]];
						}

						if (items.length) {
							for (var i = 0; i < items.length; i++) {
								var id = items[i].split('_')[0],
									item = _class.getItems(id);

								if (item) {
									if (_class.getCurrentItems(id) || !_class.existMode) {
										set(id, item.cat);
									}
								} else {
									queryObject.item[id] = true;
								}
							}

							var keys = Object.keys(queryObject.item);

							if (keys.length) {
								var sendData = {
									item: keys.join(',')
								};

								$Module.getRemoteData(sendData).then(function (data) {
									if (data) {
										for (var i = 0; i < keys.length; i++) {
											var id = keys[i],
												itemData = data.items[id];

											if (itemData) {
												var catID = itemData.cat;

												if (!loadedItems[catID]) {
													loadedItems[catID] = [];
												}

												loadedItems[catID].push(id);
												set(id, catID, itemData);
											}
										}
									}

									callback();
								});
							} else {
								callback();
							}
						} else {
							callback();
						}

						function set (id, catID, itemData) {
							result.items[id] = itemData || true;

							if (!query.cat) {
								query.cat = [catID];
							} else if (query.cat.indexOf(catID) < 0) {
								query.cat.push(catID);
							}
						}
					}

					function checkQueryCats (callback) {
						var cats = $Common.getArray(query.cat),
							queryObject = {
								cat: {},
								fields: {}
							};

						if (cats.length) {
							queryParams.cat = [];

							for (var i = 0; i < cats.length; i++) {
								var id = cats[i],
									cat = _class.getCats(id);

								if (cat) {
									if (_class.getSingleCats(id)) {
										if (_class.getNormalID(id, true)) {
											set(id);
										} else {
											var count = _class.getCounts(id);

											if (count) {
												if (_class.existMode || cat.complete || cat.count == count) {
													set(id);
												} else {
													queryObject.cat[id] = true;
												}
											} else {
												queryObject.cat[id] = true;
											}
										}

										if (cat.fields && !_class.getFields(id)) {
											queryObject.fields[cat.fields] = true;
										}
									}
								} else if (!_class.existMode) {
									queryObject.cat[id] = true;
								}
							}

							var keys = Object.keys(queryObject.cat);

							if (keys.length) {
								var sendData = {
										cat: keys.join(',')
									},
									fields = Object.keys(queryObject.fields);

								if (fields.length) {
									sendData.field = fields.join(',');
								}

								$Module.getRemoteData(sendData).then(function (data) {
									if (data) {
										for (var i = 0; i < keys.length; i++) {
											var id = keys[i],
											catData = false;
												if (data.cats!=undefined){
													catData = data.cats[id];
												}
												var cat = _class.getCats(id);

											if (catData) {
												if (_class.unloadedCats[id]) {
													delete _class.unloadedCats[id];
												}

												catData.complete = true;
												set(id, catData);
											} else if (loadedItems[id]) {
												_class.unloadedCats[id] = true;

												if (cat) {
													cat.element.addClass('bxmap-nodata');
												}

												for (var j = 0; i < loadedItems[id].length; i++) {
													delete result.items[loadedItems[id][j]];
												}
											}
										}

										if (data.fields) {
											result.fields = data.fields;
										}

										if (data.items) {
											result.items = data.items;
										}
									}

									callback();
								});
							} else {
								callback();
							}
						} else {
							callback();
						}

						function set (id, catData) {
							result.cats[id] = catData || true;

							if (query.cat.indexOf(id) >= 0) {
								queryParams.cat.push(id);
							}
						}
					}
				},

				createPhones: function (values) {
					return createLinkSet({
						values: values,
						classes: {
							set: 'bxmap-phones',
							value: 'bxmap-phone'
						},
						protocol: 'tel:',
						type: 'phone'
					});
				},

				createLinks: function (values) {
					return createLinkSet({
						values: values,
						classes: {
							set: 'bxmap-links',
							value: 'bxmap-url'
						},
						type: 'url'
					});
				},

				getItemCoords: function (data, id) {
					var geo = {},
						params = [
							'lat',
							'lng'
						],
						count = params.length;

					for (var i = params.length, result; i--;) {
						result = this.getValue(data, params[i], id);

						if (result) {
							geo[params[i]] = clearCoords(result);
							count--;
						}
					}

					if (!count) {
						return geo;
					} else {
						return false;
					}

					function clearCoords (value) {
						return parseFloat(value.toString().replace(',', '.'));
					}
				},

				createGeoLink: function () {
					return $Common.createElement(
						'*div',
						'bxmap-item-geo',
						{
							title: $Module.get('interfaceText').showMarker,
							'data-action': 'geo'
						}
					);
				},

				createRouteLink: function (classes) {
					return $Common.createElement(
						'*div',
						'bxmap-item-direction',
						{
							title: $Module.get('interfaceText').createRoute,
							'data-action': 'direction'
						}
					).text($Module.get('interfaceText').route);
				}
			};

			return _class;

			function createLinkSet (options) {
				if (/<a\s+href=/.test(options.values)) {
					return options.values;
				} else {
					var classes = {
							set: ['bxmap-set'],
							item: ['bxmap-set-item'],
							value: ['bxmap-set-value']
						},
						links = $Common.getArray(options.values),
						protocol = options.protocol ? options.protocol : '';

					if (options.classes) {
						if (options.classes.set) {
							classes.set.push(options.classes.set);
						}

						if (options.classes.item) {
							classes.item.push(options.classes.item);
						}

						if (options.classes.value) {
							classes.value.push(options.classes.value);
						}
					}

					var set = $Common.createElement('*ul', classes.set.join(' '));

					for (var i = 0; i < links.length; i++) {
						var result = $Common.normalizeValue(links[i], options.type),
							item = $Common.createElement('*li', classes.item.join(' ')).appendTo(set);

						item.append(
							$Common.createElement('a', classes.value.join(' '), {
								href: protocol + result.value
							}).text(result.text || result.value)
						);

						if (result.add) {
							item.append(result.add);
						}
					}

					return set;
				}
			}

			function setProperty (name, id) {
				if (id) {
					_class[name][id] = true;
				} else {
					_class[name] = {};
				}
			}

			function getProperty (name, id) {
				if (id) {
					return _class[name][id];
				} else {
					return Object.keys(_class[name]);
				}
			}

			function getSingleProperty (name, id) {
				if (id) {
					return _class[name][id];
				} else {
					return _class[name];
				}
			}

			function joinFilterValue (value) {
				switch ($Common.getType(value)) {
					case 'String':
						return value;
					case 'Array':
						return value.join('^');
					default:
						return '';
				}
			}

			function splitFilterValue (value) {
				switch ($Common.getType(value)) {
					case 'String':
						return decodeURIComponent(value).split('^');
					case 'Array':
						return value;
					default:
						return '';
				}
			}
		}(),
		$Map = function () {
			var mapType,
				pageType,
				wrapper,
				container,
				maxZoom,
				_class = {
					bounds: {
						lat: [],
						lng: []
					},
					markersdata: [],
					defaultZoom: 12,

					init: function (options) {
						wrapper = options.wrapper;
						mapType = options.mapType;
						pageType = options.pageType;

						if (mapType == 'google') {
							return 'callback';
						} else if (mapType == 'yandex') {
							return 'onload';
						}
					},

					getWrapper: function () {
						return $wrapper;
					},

					getContainer: function () {
						return container;
					},

					mapInitialize: function (status) {
						var defer = $.Deferred();

						if (mapType == 'google') {
							if (window.google && google.maps) {
								if (!status) {
									done();
								}
							}
						} else if (mapType == 'yandex') {
							if (window.ymaps && ymaps.ready) {
								ymaps.ready(function () {
									done();
								});
							}
						}

						return defer.promise();

						function done () {
							defer.resolve();
						}
					},

					createMap: function (options) {
						var defer = $.Deferred();

						if (this.Map) {
							defer.resolve(this.Map);
						}

						container = options.container;

						var mapOptions = {},
							mapBounds = this.getBounds(options.bounds);

						if (mapType == 'google') {
							$.extend(mapOptions, {
								mapTypeId: google.maps.MapTypeId.ROADMAP,
								mapTypeControl: false,
								draggable: true,
								zoom: this.defaultZoom,
								center: mapBounds.center,
								zoomControl: false,
								panControl: false,
								streetViewControl: false
							});

							this.Map = new google.maps.Map(container.get(0), mapOptions);

							if (mapBounds.bounds) {
								_class.Map.fitBounds(mapBounds.bounds);
							}

							google.maps.event.addListener(this.Map, 'idle', function () {
								var params = _class.Map.mapTypes[_class.Map.getMapTypeId()];

								if (params) {
									maxZoom = params.maxZoom;

									if (_class.cluster) {
										_class.cluster.redraw();
									}
								}

								$Module.getWrapper().trigger('map:idle');
							});
							google.maps.event.addListener(_class.Map, 'zoom_changed', function () {
								var params = _class.Map.mapTypes[_class.Map.getMapTypeId()];

								if (params) {
									maxZoom = params.maxZoom;

									if (_class.cluster) {
										_class.cluster.react();
									}
								}
							});

							if (maxZoom < _class.Map.getZoom()) {
								this.changeZoom(maxZoom);
							}
							google.maps.event.addListenerOnce(this.Map, 'idle', done);
						} else if (mapType == 'yandex') {
							$.extend(mapOptions, {
								controls: []
							});

							if (mapBounds.bounds) {
								$.extend(mapOptions, {
									bounds: mapBounds.bounds
								});
							} else if (mapBounds.center) {
								$.extend(mapOptions, {
									center: mapBounds.center,
									zoom: this.defaultZoom
								});
							}

							this.Map = new ymaps.Map(container.get(0), mapOptions);
							this.Map.zoomRange.get().done(function (zoom) {
								maxZoom = zoom[zoom.length - 1];
								_class.Map.options.set('maxZoom', maxZoom);
								_class.Map.setZoom(Math.round(Math.floor(_class.Map.getZoom())));
								done();
							});
						}

						return defer.promise();

						function done () {
							wrapper.trigger('map:complete');
							defer.resolve(_class.Map);
						}
					},

					refreshMap: function (status) {
						if (!this.Map) {
							return;
						}

						var center = this.Map.getCenter();

						if (mapType == 'google') {
							google.maps.event.trigger(this.Map, 'resize');
						} else if (mapType == 'yandex') {
							this.Map.container.fitToViewport();
						}

						if (status) {
							this.fitMap();
						} else {
							this.Map.setCenter(center);
						}
					},

					destroyMap: function () {
						if (mapType == 'google') {
							this.getContainer().empty();
						} else if (mapType == 'yandex') {
							this.Map.destroy();
						}

						delete this.Map;
					},

					createPoint: function (coords) {
						switch (mapType) {
							case 'google':
								return new google.maps.LatLng(coords.lat, coords.lng);
							case 'yandex':
								return [coords.lat, coords.lng];
						}
					},

					getBounds: function (bounds) {
						bounds = bounds || $Temp.getMapBounds() || $Module.get('mapBounds');

						try {
							if (!bounds.lat.length || !bounds.lng.length) {
								return null;
							}

							bounds.lat.sort();
							bounds.lng.sort();

							if (
								bounds.lat[0] == bounds.lat[bounds.lat.length - 1] ||
								bounds.lng[0] == bounds.lat[bounds.lng.length - 1]
							) {
								return {
									center: this.createPoint({
										lat: bounds.lat[0],
										lng: bounds.lng[0]
									})
								};
							}

							var mapBounds,
								corners = [
									this.createPoint({
										lat: bounds.lat[0],
										lng: bounds.lng[0]
									}),
									this.createPoint({
										lat: bounds.lat[bounds.lat.length - 1],
										lng: bounds.lng[bounds.lng.length - 1]
									})
								];

							switch (mapType) {
								case 'google':
									mapBounds = new google.maps.LatLngBounds(
										corners[0],
										corners[1]
									);
									break;
								case 'yandex':
									mapBounds = corners;
									break;
							}

							return {
								bounds: mapBounds,
								center: this.createPoint({
									lat: (bounds.lat[0] + bounds.lat[bounds.lat.length - 1]) / 2,
									lng: (bounds.lng[0] + bounds.lng[bounds.lng.length - 1]) / 2
								})
							};
						} catch (error) {
							return null;
						}
					},

					getLatLngCoords: function (object) {
						switch (mapType) {
							case 'google':
								return {
									lat: object.lat(),
									lng: object.lng()
								};
							case 'yandex':
								return {
									lat: object[0],
									lng: object[1]
								};
						}
					},

					fitMap: function (bounds) {
						var mapBounds = this.getBounds(bounds);

						if (mapBounds) {
							if (mapBounds.bounds) {
								if (mapType == 'google') {
									this.Map.fitBounds(mapBounds.bounds);
								} else if (mapType == 'yandex') {
									this.Map.setBounds(mapBounds.bounds);
								}
							} else if (mapBounds.center) {
								if (mapType == 'google') {
									this.Map.setOptions({
										center: mapBounds.center,
										zoom: this.defaultZoom
									});
								} else if (mapType == 'yandex') {
									this.Map.setCenter(mapBounds.center, this.defaultZoom);
								}
							}
						}

						return this.Map;
					},

					centerMap: function (marker) {
						var coords;

						if (mapType == 'google') {
							coords = marker.getPosition();
						} else if (mapType == 'yandex') {
							coords = marker.geometry.getCoordinates();
						}

						this.Map.setCenter(coords);
						return this.Map;
					},

					changeZoom: function (zoom) {
						if (!$Common.getType(zoom, 'number')) {
							zoom = Math.round(this.Map.getZoom()) - Math.pow(-1, !!zoom * 1);
						}

						if (mapType == 'google') {
							if (zoom <= maxZoom) {
								this.Map.setZoom(zoom);
							}
						} else if (mapType == 'yandex') {
							this.Map.setZoom(zoom, {
								checkZoomRange: true
							});
						}
					},

					panToMarker: function (marker) {
						this.Map.panTo(this.getCoords(marker));
					},

					getCoords: function (marker) {
						if (mapType == 'google') {
							return marker.getPosition();
						} else if (mapType == 'yandex') {
							return marker.geometry.getCoordinates();
						}
					},

					createMarker: function (id, options) {
						var point = options.point || this.createPoint(options.coords),
							marker,
							markerName = pageType.slice(0, -1) + 'Marker';

						$Temp.setMapBounds(options.coords);

						if (mapType == 'google') {
							marker = new google.maps.Marker({
								icon: options.icon['_' + options.status],
								position: point,
								title: options.name
							});

							if (id) {
								if (pageType == 'routes') {
									google.maps.event.addListener(marker, 'mouseover', function () {
										wrapper.trigger(markerName + ':hover', [id]);
									});
									google.maps.event.addListener(marker, 'mouseout', function () {
										wrapper.trigger(markerName + ':out', [id]);
									});
								}

								google.maps.event.addListener(marker, 'click', function () {
									wrapper.trigger(markerName + ':click', [id]);
								});
							}
						} else if (mapType == 'yandex') {
							marker = new ymaps.Placemark(
								point,
								{
									hintContent: options.name
								},
								{
									preset: options.catID + '#' + options.status
								}
							);

							if (id) {
								if (pageType == 'routes') {
									marker.events.add('mouseenter', function (e) {
										wrapper.trigger(markerName + ':hover', [id]);
									});
									marker.events.add('mouseleave', function (e) {
										wrapper.trigger(markerName + ':out', [id]);
									});
								}

								marker.events.add('click', function (e) {
									wrapper.trigger(markerName + ':click', [id]);
								});
							}
						}

						if (id) {
							marker.id = id;
						}

						if (options.catID) {
							marker.catID = options.catID;
						}

						return {
							marker: marker,
							point: point
						};
					},

					createPolyLine: function (id, coords, options, action) {
						var polyLine;

						if (mapType == 'google') {
							polyLine = new google.maps.Polyline($Common.clone(options, {
								path: coords
							}));

							if (action) {
								google.maps.event.addListener(polyLine, 'click', function () {
									wrapper.trigger('route:click', [id]);
								});
								google.maps.event.addListener(polyLine, 'mouseover', function () {
									wrapper.trigger('route:hover', [id]);
								});
								google.maps.event.addListener(polyLine, 'mouseout', function () {
									wrapper.trigger('route:out', [id]);
								});
							}
						} else if (mapType == 'yandex') {
							polyLine = new ymaps.Polyline(coords, options);

							if (action) {
								polyLine.events.add('click', function (e) {
									wrapper.trigger('route:click', [id]);
								});
								polyLine.events.add('mouseenter', function (e) {
									wrapper.trigger('route:hover', [id]);
								});
								polyLine.events.add('mouseleave', function (e) {
									wrapper.trigger('route:out', [id]);
								});
							}
						}

						return polyLine;
					},

					showMarker: function (marker) {
						if (mapType == 'google') {
							marker.setMap(this.Map);
						} else if (mapType == 'yandex') {
							this.Map.geoObjects.add(marker);
						}

						return marker;
					},

					removeMarker: function (marker) {
						if (mapType == 'google') {
							marker.setMap(null);
						} else if (mapType == 'yandex') {
							this.Map.geoObjects.remove(marker);
						}

						return marker;
					},

					setMarkerView: function (marker, icon, catID, status) {
						if (mapType == 'google') {
							marker.setIcon(icon['_' + status]);

							if (status == $Temp.activeStatus) {
								marker.setZIndex(1000);
							} else {
								marker.setZIndex(0);
							}
						} else if (mapType == 'yandex') {
							marker.options.set('preset', catID + '#' + status);
						}

						return marker;
					},

					showRoute: function (id, status) {
						var map = this.Map,
							route = $Temp.getItems(id);

						this.setRouteView(id, status);

						if (mapType == 'google') {
							if (route.polyLine) {
								route.polyLine.setMap(map);
							}

							for (var i = route.points.length; i--;) {
								$Temp.getMarker(route.points[i]).setMap(map);
							}
						} else if (mapType == 'yandex') {
							if (route.polyLine) {
								map.geoObjects.add(route.polyLine);
							}

							for (var i = route.points.length; i--;) {
								map.geoObjects.add($Temp.getMarker(route.points[i]));
							}
						}

						return route;
					},

					removeRoute: function (id) {
						var map = this.Map,
							route = $Temp.getItems(id);

						if (mapType == 'google') {
							if (route.polyLine) {
								route.polyLine.setMap(null);
							}

							for (var i = route.points.length; i--;) {
								$Temp.getMarker(route.points[i]).setMap(null);
							}
						} else if (mapType == 'yandex') {
							if (route.polyLine) {
								map.geoObjects.remove(route.polyLine);
							}

							for (var i = route.points.length; i--;) {
								map.geoObjects.remove($Temp.getMarker(route.points[i]));
							}
						}

						return route;
					},

					updateRoutes: function (set) {
						var list = $Temp.getCurrentItems();

						for (var i = list.length; i--;) {
							var id = list[i];

							if (id in set) {
								this.showRoute(id);
							} else {
								this.removeRoute(id);
							}
						}
					},

					setPathView: function (path, icon, type, status) {
						if (mapType == 'google') {
							path.setOptions(icon['_path' + $Common.getName(status)]);
						} else if (mapType == 'yandex') {
							path.options.set('preset', type + 'path#' + status);
						}

					},

					setRouteView: function (id, status) {
						if (!id) {
							return;
						}

						var route = $Temp.getItems(id),
							icon = $Temp.getRouteIcon(),
							mainStatus = status || $Temp.defaultStatus,
							startStatus = mainStatus,
							endStatus = mainStatus;

						route.status = mainStatus;

						if (mainStatus == $Temp.activeStatus) {
							startStatus = 'start';
							endStatus = 'end';
						}

						if (route.polyLine) {
							this.setPathView(
								route.polyLine,
								icon,
								'route',
								mainStatus
							);
						}

						setView(id, startStatus);

						if (route.points.length) {
							if (route.end) {
								setView(route.points[route.points.length - 1], endStatus);
							}

							for (var i = 1; i < route.points.length - 1; i++) {
								setView(route.points[i], mainStatus);
							}
						}

						return route;

						function setView (id, status) {
							_class.setMarkerView($Temp.getMarker(id), icon, 'route', status);
						}
					},

					createIconSet: function (catID, catData, iconCatData, universalMarker) {
						var options = {},
							href = iconCatData.url,
							offset = iconCatData.logo ? iconCatData.logo[1] : 0;

						if (catData.icon) {
							href = $Temp.getFullPath(catData.icon, 'images');
						} else if (universalMarker) {
							catData.pos = 0;
						}

						if (mapType == 'google') {
							options._default = {
								url: href,
								size: new google.maps.Size(iconCatData.size[0], iconCatData.size[1]),
								anchor: new google.maps.Point(iconCatData.anchor[0], iconCatData.anchor[1]),
								origin: new google.maps.Point(catData.pos, offset)
							};
							options._active = $Common.clone(options._default, {
								origin: new google.maps.Point(catData.pos, offset + iconCatData.size[1])
							});
						} else if (mapType == 'yandex') {
							options._default = {
								iconLayout: $Temp.defaultStatus + '#image',
								iconImageHref: href,
								iconImageSize: [iconCatData.size[0], iconCatData.size[1]],
								iconImageOffset: [- iconCatData.anchor[0], - iconCatData.anchor[1]],
								iconImageClipRect: [
									[catData.pos, offset],
									[catData.pos + iconCatData.size[0], offset + iconCatData.size[1]]
								],
								hideIconOnBalloonOpen: false
							};
							options._active = $Common.clone(options._default, {
								iconImageClipRect: [
									[catData.pos, offset + iconCatData.size[1]],
									[catData.pos + iconCatData.size[0], offset + iconCatData.size[1] * 2]
								]
							});
							ymaps.option.presetStorage.add(catID + '#' + $Temp.defaultStatus, options._default);
							ymaps.option.presetStorage.add(catID + '#' + $Temp.activeStatus, options._active);
						}

						return options;
					},

					createRouteIconSet: function (icon, path, type) {
						var options = {},
							offset = icon.logo ? icon.logo[1] : 0;

						if (mapType == 'google') {
							options._start = {
								url: icon.url,
								size: new google.maps.Size(icon.size[0], icon.size[1]),
								anchor: new google.maps.Point(icon.anchor[0], icon.anchor[1]),
								origin: new google.maps.Point(0, offset)
							};
							options._end = $Common.clone(options._start, {
								origin: new google.maps.Point(icon.size[0], offset)
							});
							options._default = {
								url: icon.url,
								size: new google.maps.Size(path.def.size[0], path.def.size[1]),
								anchor: new google.maps.Point(path.def.anchor[0], path.def.anchor[1]),
								origin: new google.maps.Point(path.def.offset[0], path.def.offset[1])
							};
							options._hover = $Common.clone(options._default, {
								origin: new google.maps.Point(path.def.offset[0], path.def.offset[1] + path.def.size[1])
							});
							options._pathDefault = {
								strokeColor: path.strokeColor,
								strokeOpacity: path.strokeOpacity,
								strokeWeight: path.strokeWeight
							};

							if (path.active !== undefined) {
								options._active = {
									url: icon.url,
									size: new google.maps.Size(path.active.size[0], path.active.size[1]),
									anchor: new google.maps.Point(path.active.anchor[0], path.active.anchor[1]),
									origin: new google.maps.Point(path.active.offset[0], path.active.offset[1])
								};
								options._super = $Common.clone(options._active, {
									origin: new google.maps.Point(path.active.offset[0], path.active.offset[1] + path.active.size[1])
								});
							}

							if (path.strokeOpacityHover !== undefined) {
								options._pathHover = $Common.clone(options._pathDefault, {
									strokeOpacity: path.strokeOpacityHover
								});
							}

							if (path.strokeColorActive !== undefined) {
								options._pathActive = $Common.clone(options._pathHover, {
									strokeColor: path.strokeColorActive
								});
							}
						} else if (mapType == 'yandex') {
							options._start = {
								iconLayout: $Temp.defaultStatus + '#image',
								iconImageHref: icon.url,
								iconImageSize: [icon.size[0], icon.size[1]],
								iconImageOffset: [- icon.anchor[0], - icon.anchor[1]],
								iconImageClipRect: [
									[0, offset],
									[icon.size[0], offset + icon.size[1]]
								],
								hideIconOnBalloonOpen: false
							};
							options._end = $Common.clone(options._start, {
								iconImageClipRect: [
									[icon.size[0], offset],
									[icon.size[0] * 2, offset + icon.size[1]]
								]
							});
							options._default = $Common.clone(options._start, {
								iconImageSize: [path.def.size[0], path.def.size[1]],
								iconImageClipRect: [
									[path.def.offset[0], path.def.offset[1]],
									[path.def.offset[0] + path.def.size[0], path.def.offset[1] + path.def.size[1]]
								],
								iconImageOffset: [- path.def.anchor[0], - path.def.anchor[1]]
							});
							options._hover = $Common.clone(options._default, {
								iconImageClipRect: [
									[path.def.offset[0], path.def.offset[1] + path.def.size[1]],
									[path.def.offset[0] + path.def.size[0], path.def.offset[1] + path.def.size[1] * 2]
								]
							});
							options._pathDefault = {
								strokeColor: path.strokeColor,
								strokeOpacity: path.strokeOpacity,
								strokeWidth: path.strokeWeight
							};
							ymaps.option.presetStorage.add(type + '#start', options._start);
							ymaps.option.presetStorage.add(type + '#end', options._end);
							ymaps.option.presetStorage.add(type + '#' + $Temp.defaultStatus, options._default);
							ymaps.option.presetStorage.add(type + '#hover', options._hover);
							ymaps.option.presetStorage.add(type + 'path#' + $Temp.defaultStatus, options._pathDefault);
							ymaps.option.presetStorage.add(type + '#invisible', $Common.clone(options._start, {
								iconImageClipRect: [
									[0, 0],
									[0, 0]
								],
								iconImageSize: [0, 0]
							}));

							if (path.active !== undefined) {
								options._active = $Common.clone(options._default, {
									iconImageClipRect: [
										[path.active.offset[0], path.active.offset[1]],
										[path.active.offset[0] + path.active.size[0], path.active.offset[1] + path.active.size[1]]
									],
									iconImageOffset: [- path.active.anchor[0], - path.active.anchor[1]]
								});
								options._super = $Common.clone(options._active, {
									iconImageClipRect: [
										[path.active.offset[0], path.active.offset[1] + path.active.size[1]],
										[path.active.offset[0] + path.active.size[0], path.active.offset[1] + path.active.size[1] * 2]
									]
								});
								ymaps.option.presetStorage.add(type + '#' + $Temp.activeStatus, options._active);
								ymaps.option.presetStorage.add(type + '#super', options._super);
							}

							if (path.strokeOpacityHover !== undefined) {
								options._pathHover = $Common.clone(options._pathDefault, {
									strokeOpacity: path.strokeOpacityHover
								});
								ymaps.option.presetStorage.add(type + 'path#hover', options._pathHover);
							}

							if (path.strokeColorActive !== undefined) {
								options._pathActive = $Common.clone(options._pathHover, {
									strokeColor: path.strokeColorActive
								});
								ymaps.option.presetStorage.add(type + 'path#' + $Temp.activeStatus, options._pathActive);
							}
						}

						return options;
					},

					setClusterOptions: function (data, template) {
						var dimensionSet = [],
							numberSet = [],
							anchor = data.anchor || [0, 0];

						dimensionSet = [];
						numberSet = [];

						$Common.createRule(
							'.bxmap-cluster',
							[
								'background-image:url(' + data.icon + ');',
								'color:' + data.color + ';'
							].join('')
						);

						for (var i = 0, dim = 0, pos = 0; i < data.set.length; i++) {
							dim = data.set[i].size;

							var def = [
									'width:' + dim + 'px;',
									'height:' + dim + 'px;',
									'line-height:' + dim + 'px;'
								],
								drop = [pos, 0],
								group = [pos, dim],
								active = [pos, dim * 2];

							if (data.set[i].color) {
								def.push(
									'color:' + data.set[i].color + ';'
								);
							}

							if (data.set[i].icon) {
								def.push(
									'background-image:url(' + data.set[i].icon + ');'
								);
								drop[0] = 0;
								group[0] = 0;
								active[0] = 0;
							}

							def.push(
								'background-position:-' + drop[0] + 'px -' + drop[1] + 'px;'
							);
							$Common.createRule(
								'.bxmap-cluster.bxmap-cluster-count-' + (i + 1),
								def.join('')
							);
							$Common.createRule(
								'.bxmap-cluster.bxmap-cluster-group.bxmap-cluster-count-' + (i + 1),
								'background-position:-' + group[0] + 'px -' + group[1] + 'px;'
							);
							$Common.createRule(
								'.bxmap-cluster.bxmap-cluster-group.bxmap-active.bxmap-cluster-count-' + (i + 1),
								'background-position:-' + active[0] + 'px -' + active[1] + 'px;'
							);
							dimensionSet.push({
								size: [dim, dim],
								offset: [- dim / 2 - anchor[0], - dim / 2 - anchor[1]]
							});
							pos += dim;

							if (i) {
								numberSet.push(Math.pow(10, i));
							}
						}

						if (mapType == 'google') {
							this.clusterIconOptions = {
								averageCenter: true,
								template: template,
								gridSize: data.gridSize,
								set: dimensionSet
							};
							wrapper
								.on('mouseenter', '[data-item~="cluster"]', function () {
									$(this).css({
										zIndex: 1
									});
								})
								.on('mouseleave', '[data-item~="cluster"]', function () {
									$(this).css({
										zIndex: 'auto'
									});
								});
						} else if (mapType == 'yandex') {
							var clusterLayout = ymaps.templateLayoutFactory.createClass(template.get(0).outerHTML, {
								build: function () {
									var _self = this,
										markers = this.getData().properties.get('geoObjects'),
										count = Math.ceil(Math.log(markers.length) / Math.log(10));

									this.constructor.superclass.build.call(this);
									this.container_ = $(this.getParentElement()).find('.bxmap-cluster')
									.html(markers.length)
									.addClass('bxmap-cluster-count-' + count);

									this.target = this.getData().geoObject;
									this.objects = typeof this.target.getGeoObjects == 'function' && this.target.getGeoObjects();

									if (this.objects) {
										this.bounds = this.target.getBounds();
										this.group = this.bounds[0][0] == this.bounds[1][0] && this.bounds[0][1] == this.bounds[1][1];

										if (this.group) {
											this.container_.addClass('bxmap-cluster-group');
										}
									}

									if (this.target == _class.activeCluster) {
										this.container_.addClass('bxmap-active');
									} else {
										this.container_.removeClass('bxmap-active');
									}

									this.target.events.add('marker:' + $Temp.defaultStatus, this.unsetActive, this);
									this.target.events.add('marker:' + $Temp.activeStatus, this.setActive, this);
									this.target.events.add('click', this.onclick, this);
								},

								onclick: function (e) {
									e.preventDefault();

									if (this.objects) {
										if (this.group) {
											if (this.isActive()) {
												_class.setClusterView();
												wrapper.trigger('cluster:detected');
											} else {
												_class.setClusterView(this.target);
												wrapper.trigger('cluster:detected', [this.objects, this.bounds[0]]);
											}
										} else {
											_class.Map.setBounds(this.bounds);
										}
									}
								},

								isActive: function () {
									return this.container_.hasClass('bxmap-active');
								},

								setActive: function () {
									this.container_.addClass('bxmap-active');
								},

								unsetActive: function () {
									this.container_.removeClass('bxmap-active');
								},

								clear: function () {
									this.target.events.remove('marker:' + $Temp.defaultStatus, this.unsetActive, this);
									this.target.events.remove('marker:' + $Temp.activeStatus, this.setActive, this);
									this.target.events.remove('click', this.onclick, this);
									this.constructor.superclass.clear.call(this);
								}
							});

							this.cluster = new ymaps.Clusterer({
								clusterIcons: dimensionSet,
								clusterNumbers: numberSet,
								clusterIconContentLayout: clusterLayout,
								clusterOpenBalloonOnClick: false,
								clusterDisableClickZoom: true,
								gridSize: data.gridSize
							});
						}
					},

					setClusterView: function (cluster) {
						if (mapType == 'google') {
							if (this.activeCluster) {
								this.activeCluster.unsetActive();
								delete this.activeCluster;
							}

							if (cluster) {
								setTimeout($.proxy(function () {
									this.activeCluster = cluster;
									cluster.setActive();
								}, this), 0);
							}
						} else if (mapType == 'yandex') {
							if (this.activeCluster) {
								this.activeCluster.events.fire('marker:' + $Temp.defaultStatus);
							}

							if (cluster) {
								setTimeout($.proxy(function () {
									this.activeCluster = cluster;
									cluster.events.fire('marker:' + $Temp.activeStatus);
								}, this), 0);
							}
						}
					},

					updateMarkerCluster: function (data) {
						if (mapType == 'google') {
							if (this.cluster) {
								this.cluster.removeMarkers(this.markersdata);
							}

							this.markersdata = $Common.values(data);
							this.cluster = new MarkerClusterer();
						} else if (mapType == 'yandex') {
							if (!this.cluster.getMap()) {
								this.Map.geoObjects.add(this.cluster);
							}

							this.cluster.removeAll();
							this.markersdata = $Common.values(data);

							if (this.markersdata && this.markersdata.length) {
								this.cluster.add(this.markersdata);

								var clusters = this.cluster.getClusters();

								if (clusters.length) {
									_class.clusterLength = clusters.length;

									if (_class.clusterLength) {
										wrapper.trigger('cluster:done', [clusters.length]);
									} else {
										wrapper.trigger('cluster:done');
									}
								} else {
									wrapper.trigger('cluster:done');
								}
							} else if (_class.clusterLength) {
								delete _class.clusterLength;
								wrapper.trigger('cluster:done');
							}
						}
					},

					getMarkerInCluster: function (ids, options) {
						var defer = $.Deferred(),
							name,
							result;

						if ($Common.getType(ids, 'string')) {
							var ids_ = ids;

							ids = {};
							ids[ids_] = true;
						}

						if (mapType == 'google') {
							name = 'getMarker';
							result = getCluster(_class.cluster.clusters_);

							if (result) {
								result.equal = result.bounds.getNorthEast().equals(result.bounds.getSouthWest());
							}

							createDecision(function (bounds) {
								google.maps.event.addListenerOnce(_class.Map, 'zoom_changed', waitingZoom);
								_class.Map.fitBounds(bounds);
							});
						} else if (mapType == 'yandex') {
							if (options.marker && options.marker.getMap()) {
								defer.resolve(null);
							} else {
								name = 'getGeoObjects';
								result = getCluster(this.cluster.getClusters());

								if (result) {
									result.equal = result.bounds[0][0] == result.bounds[1][0] && result.bounds[0][1] == result.bounds[1][1];
								}

								createDecision(function (bounds) {
									_class.Map.events.once('boundschange', waitingZoom);
									_class.Map.setBounds(bounds);
								});
							}
						}

						function createDecision (callback) {
							if (result) {
								if (options.noZoom || result.equal) {
									defer.resolve(result.cluster, result.cluster[name](), result.bounds);
								} else {
									callback(result.bounds);
								}
							} else {
								defer.resolve(null);
							}
						}

						function waitingZoom () {
							defer.resolve(null);
						}

						function getCluster (clusters) {
							if (clusters) {
								for (var i = clusters.length; i--;) {
									var markers = clusters[i][name]();

									if (markers.length > 1) {
										for (var j = markers.length; j--;) {
											if (markers[j].id in ids) {
												return {
													cluster: clusters[i],
													bounds: clusters[i].getBounds()
												};
											}
										}
									}
								}
							}

							return null;
						}

						return defer.promise();
					},

					getGeoPoint: function (value) {
						var defer = $.Deferred(),
							center = this.getBounds().center,
							request = {};

						if (mapType == 'google') {
							if ($Common.getType(value, 'string')) {
								request.address = value;
								request.bounds = new google.maps.LatLngBounds(center, center);
							} else {
								request.location = value;
							}

							new google.maps.Geocoder().geocode(request, function (result, status) {
								if (status == 'OK') {
									defer.resolve({
										point: result[0].geometry.location,
										address: result[0].formatted_address
									});
								} else {
									defer.resolve({
										error: status == 'ZERO_RESULTS' ? 'NOT_FOUND' : status
									});
								}
							});
						} else if (mapType == 'yandex') {
							if ($Common.getType(value, 'string')) {
								request.boundedBy = [center, center];
							}

							ymaps.geocode(value, request).then(
								function (result) {
									if (result.metaData.geocoder.found) {
										defer.resolve({
											point: result.geoObjects.get(0).geometry.getCoordinates(),
											address: result.geoObjects.get(0).properties.get('text')
										});
									} else {
										defer.resolve({
											error: 'NOT_FOUND'
										});
									}
								},
								function (error) {
									defer.resolve({
										error: error
									});
								}
							);
						}

						return defer.promise();
					},

					getRoute: function (routePoints, routeType, icon, options, edge) {
						var defer = $.Deferred();

						if (mapType == 'google') {
							new google.maps.DirectionsService().route({
								origin: routePoints[0].point,
								destination: routePoints[1].point,
								travelMode: routeType
							}, function (response, status) {
								if (status == google.maps.DirectionsStatus.OK) {
									var routeData = response.routes[0],
										legsData = routeData.legs[0],
										steps = legsData.steps,
										route = {
											polyLine: new google.maps.DirectionsRenderer({
												suppressMarkers: true,
												polylineOptions: options
											}),
											bounds: routeData.bounds,
											duration: legsData.duration.value,
											length: legsData.distance.value,
											humanDuration: legsData.duration.text,
											humanLength: legsData.distance.text,
											warnings: routeData.warnings,
											copyrights: routeData.copyrights,
											segments: []
										};

									route.polyLine.setDirections(response);

									if (edge) {
										var index = edge == 'start' ? 0 : 1,
											object = routePoints[index];

										if (!$Data.withoutMap) {
											route[edge + 'Marker'] = _class.createMarker(edge, {
												name: object.address,
												point: object.point,
												catID: null,
												icon: icon,
												status: edge
											}).marker;
										}
									}

									for (var i = 0; i < steps.length; i++) {
										route.segments.push({
											length: steps[i].distance.value,
											time: steps[i].duration.value,
											action: getAction(steps[i].maneuver),
											humanLength: steps[i].distance.text,
											humanTime: steps[i].duration.text,
											text: steps[i].instructions.replace(/<(\w+)(\s[^>]+)*>/g, '<$1 class="instructions">')
										});
									}

									defer.resolve({
										result: route,
										steps: steps
									});
								} else {
									defer.resolve({
										error: status
									});
								}
							});
						} else if (mapType == 'yandex') {
							ymaps.route(
								[
									routePoints[0].point,
									routePoints[1].point
								],
								{
									mapStateAutoApply: true
								}
							).then(
								function (response) {
									var pathes = response.getPaths(),
										wayPoints = response.getWayPoints(),
										steps = [],
										route = {
											polyLine: pathes,
											bounds: response.properties.get('boundedBy'),
											duration: response.getTime(),
											length: response.getLength(),
											humanDuration: response.getHumanTime(),
											humanLength: response.getHumanLength(),
											copyrights: 'РљР°СЂС‚РѕРіСЂР°С„РёС‡РµСЃРєРёРµ РґР°РЅРЅС‹Рµ В© ' + new Date().getFullYear() + ' Yandex',
											segments: []
										};

									_class.setPathView(
										route.polyLine,
										icon,
										'direction',
										$Temp.defaultStatus
									);

									if (edge) {
										var name = edge + 'Marker',
											index = 0;

										switch (edge) {
											case 'start':
												route[name] = wayPoints.get(0);
												break;
											case 'end':
												index = 1;
												route[name] = wayPoints.get(wayPoints.getLength() - 1);
												break;
										}

										route[name].properties.set('iconContent', routePoints[index].address);
										_class.setMarkerView(route[name], icon, 'direction', edge);
									}

									for (var i = 0; i < pathes.getLength(); i++) {
										var segments = pathes.get(i).getSegments();

										steps = steps.concat(segments);

										for (var j = 0; j < segments.length; j++) {
											var text = $Common.getName(segments[j].getHumanAction());

											if (segments[j].getStreet()) {
												text += ', ' + segments[j].getStreet();
											}

											route.segments.push({
												length: segments[j].getLength(),
												time: segments[j].getTime(),
												action: getAction(segments[j].getAction()),
												humanLength: segments[j].getHumanLength(),
												humanTime: segments[j].getHumanTime(),
												text: text
											});
										}
									}

									defer.resolve({
										result: route,
										steps: steps
									});
								},
								function (error) {
									defer.resolve({
										error: error.message
									});
								}
							);
						}

						function getAction (action) {
							if (!action) {
								return '';
							}

							var maneuver = [];

							if (/\bright\b/.test(action)) {
								maneuver.unshift('right');
							} else if (/(\bleft\b|\bback\b|\benter\b)/.test(action)) {
								maneuver.unshift('left');
							}

							if (maneuver.length) {
								if (/\bslight\b/.test(action)) {
									maneuver.unshift('slight');
								} else if (/(\bhard\b|\bsharp\b)/.test(action)) {
									maneuver.unshift('hard');
								} else if (/(\buturn\b|\bback\b)/.test(action)) {
									maneuver.unshift('back');
								} else if (/(\bramp\b|\bexit\b)/.test(action)) {
									maneuver.unshift('exit');
								} else if (/\bfork\b/.test(action)) {
									maneuver.unshift('fork');
								} else if (/\bkeep\b/.test(action)) {
									maneuver.unshift('keep');
								} else if (/\bmerge\b/.test(action)) {
									maneuver.unshift('merge');
								} else if (/\broundabout\b/.test(action)) {
									if (/\bleave\b/.test(action)) {
										maneuver.unshift('exit', 'roundabout');
									} else {
										maneuver.unshift('enter', 'roundabout');
									}
								}
							} else {
								if (/\bexit\b/.test(action)) {
									maneuver.unshift('exit');
								} else if (/\bmerge\b/.test(action)) {
									maneuver.unshift('merge');
								} else if (/\broundabout\b/.test(action)) {
									maneuver = action.split(/[^\w+]/);
								} else if (/\bferry\b/.test(action)) {
									maneuver.unshift('ferry');

									if (/\btrain\b/.test(action)) {
										maneuver.unshift('train');
									} else {
										maneuver.unshift('board');
									}
								}

								if (!maneuver.length) {
									if (/(\bstraight\b|\bnone\b)/.test(action)) {
										maneuver.unshift('straight');
									}
								}
							}

							return maneuver.join('-');
						}

						return defer.promise();
					},

					addDirection: function (route) {
						if (route) {
							if (mapType == 'google') {
								route.polyLine.setMap(this.Map);

								if (!$Temp.secondMarker) {
									if (route.startMarker) {
										route.startMarker.setMap(this.Map);
									}

									if (route.endMarker) {
										route.endMarker.setMap(this.Map);
									}
								}
							} else if (mapType == 'yandex') {
								this.Map.geoObjects.add(route.polyLine);

								if (!$Temp.secondMarker) {
									if (route.startMarker) {
										this.Map.geoObjects.add(route.startMarker);
									}

									if (route.endMarker) {
										this.Map.geoObjects.add(route.endMarker);
									}
								}

								this.Map.setBounds(route.bounds);
							}
						}
					},

					removeDirection: function (route) {
						if (route) {
							if (mapType == 'google') {
								route.polyLine.setMap(null);

								if (!$Temp.secondMarker) {
									if (route.startMarker) {
										route.startMarker.setMap(null);
									}

									if (route.endMarker) {
										route.endMarker.setMap(null);
									}
								}
							} else if (mapType == 'yandex') {
								this.Map.geoObjects.remove(route.polyLine);

								if (!$Temp.secondMarker) {
									if (route.startMarker) {
										this.Map.geoObjects.remove(route.startMarker);
									}

									if (route.endMarker) {
										this.Map.geoObjects.remove(route.endMarker);
									}
								}
							}
						}
					}
				};

			function MarkerClusterer() {
				var options = _class.clusterIconOptions || {},
					markers = _class.markersdata;

				this.extend(MarkerClusterer, google.maps.OverlayView);
				this.markers_ = [];
				this.clusters_ = [];
				this.ready_ = false;
				this.template_ = options.template;
				this.gridSize_ = options.gridSize / 2 || 32;
				this.minClusterSize_ = options.minimumClusterSize || 2;
				this.maxZoom_ = options.maxZoom || null;
				this.set_ = options.set;
				this.zoomOnClick_ = options.zoomOnClick ? options.zoomOnClick : true;
				this.averageCenter_ = options.averageCenter ? options.averageCenter : false;
				this.setMap(_class.Map);
				this.prevZoom_ = _class.Map.getZoom();

				if (markers && (markers.length || Object.keys(markers).length)) {
					this.addMarkers(markers, false);
				}
			}

			MarkerClusterer.prototype = {
				constructor: MarkerClusterer,

				extend: function (obj1, obj2) {
					return (function (object) {
						for (var property in object.prototype) {
							this.prototype[property] = object.prototype[property];
						}

						return this;
					}).apply(obj1, [obj2]);
				},

				onAdd: function () {
					this.setReady_(true);
				},

				draw: function () {},

				react: function () {
					var minZoom = _class.Map.minZoom || 0,
						zoom = Math.min(Math.max(_class.Map.getZoom(), minZoom), maxZoom);

					if (this.prevZoom_ != zoom) {
						this.prevZoom_ = zoom;
						this.resetViewport();
					}
				},

				fitMapToMarkers: function () {
					var markers = this.getMarker(),
						bounds = new google.maps.LatLngBounds();

					for (var i = markers.length; i--;) {
						bounds.extend(markers[i].getPosition());
					}

					_class.Map.fitBounds(bounds);
				},

				isZoomOnClick: function () {
					return this.zoomOnClick_;
				},

				isAverageCenter: function () {
					return this.averageCenter_;
				},

				getMarker: function () {
					return this.markers_;
				},

				getTotalMarkers: function () {
					return this.markers_.length;
				},

				setMaxZoom: function (maxZoom) {
					this.maxZoom_ = maxZoom;
				},

				getMaxZoom: function () {
					return this.maxZoom_;
				},

				calculator_: function (markers, length) {
					var index = 0,
						count = markers.length,
						dv = count;

					while (dv !== 0) {
						dv = parseInt(dv / 10, 10);
						index ++;
					}

					index = Math.min(index, length);
					return {
						text: count,
						index: index
					};
				},

				setCalculator: function (calculator) {
					this.calculator_ = calculator;
				},

				getCalculator: function () {
					return this.calculator_;
				},

				addMarkers: function (markers, opt_nodraw) {
					if (markers.length) {
						for (var i = markers.length; i--;) {
							this.pushMarkerTo_(markers[i]);
						}
					} else {
						var list = Object.keys(markers);

						if (list.length) {
							for (var i = list.length; i--;) {
								this.pushMarkerTo_(markers[list[i]]);
							}
						}
					}

					if (!opt_nodraw) {
						this.redraw();
					}
				},

				pushMarkerTo_: function (marker) {
					marker.isAdded = false;

					if (marker.draggable) {
						var that = this;

						google.maps.event.addListener(marker, 'dragend', function () {
							marker.isAdded = false;
							that.repaint();
						});
					}

					this.markers_.push(marker);
				},

				addMarker: function (marker, opt_nodraw) {
					this.pushMarkerTo_(marker);

					if (!opt_nodraw) {
						this.redraw();
					}
				},

				removeMarker_: function (marker) {
					var index = this.markers_.indexOf(marker);

					if (index < 0) {
						return false;
					}

					marker.setMap(null);
					this.markers_.splice(index, 1);
					return true;
				},

				removeMarker: function (marker, opt_nodraw) {
					var removed = this.removeMarker_(marker);

					if (!opt_nodraw && removed) {
						this.resetViewport();
						this.redraw();
						return true;
					} else {
						return false;
					}
				},

				removeMarkers: function (markers, opt_nodraw) {
					var removed = false;

					for (var i = markers.length; i--;) {
						var r = this.removeMarker_(markers[i]);
						removed = removed || r;
					}

					if (!opt_nodraw && removed) {
						this.resetViewport();
						this.redraw();
						return true;
					}
				},

				setReady_: function (ready) {
					if (!this.ready_) {
						this.ready_ = ready;
						this.createClusters_();
						wrapper.trigger('cluster:done', [this.clusters_.length]);
					}
				},

				getTotalClusters: function () {
					return this.clusters_.length;
				},

				getMap: function () {
					return _class.Map;
				},

				setMap: function (map) {
					_class.Map = map;
				},

				getGridSize: function () {
					return this.gridSize_;
				},

				setGridSize: function (size) {
					this.gridSize_ = size;
				},

				getMinClusterSize: function () {
					return this.minClusterSize_;
				},

				setMinClusterSize: function (size) {
					this.minClusterSize_ = size;
				},

				getExtendedBounds: function (bounds) {
					var projection = this.getProjection(),
						tr = new google.maps.LatLng(bounds.getNorthEast().lat(), bounds.getNorthEast().lng()),
						bl = new google.maps.LatLng(bounds.getSouthWest().lat(), bounds.getSouthWest().lng()),
						trPix = projection.fromLatLngToDivPixel(tr),
						blPix = projection.fromLatLngToDivPixel(bl);

					trPix.x += this.gridSize_;
					trPix.y -= this.gridSize_;

					blPix.x -= this.gridSize_;
					blPix.y += this.gridSize_;

					bounds.extend(projection.fromDivPixelToLatLng(trPix));
					bounds.extend(projection.fromDivPixelToLatLng(blPix));

					return bounds;
				},

				isMarkerInBounds_: function (marker, bounds) {
					return bounds.contains(marker.getPosition());
				},

				clearMarkers: function () {
					this.resetViewport(true);
					this.markers_ = [];
				},

				resetViewport: function (opt_hide) {
					for (var i = this.clusters_.length; i--;) {
						this.clusters_[i].remove();
					}

					for (var i = this.markers_.length; i--;) {
						this.markers_[i].isAdded = false;

						if (opt_hide) {
							this.markers_[i].setMap(null);
						}
					}

					this.clusters_ = [];
				},

				repaint: function () {
					var oldClusters = this.clusters_.slice();

					this.clusters_.length = 0;
					this.resetViewport();
					this.redraw();

					setTimeout(function () {
						for (var i = oldClusters.length; i--;) {
							oldClusters[i].remove();
						}
					}, 0);
				},

				redraw: function () {
					this.createClusters_();
				},

				distanceBetweenPoints_: function (p1, p2) {
					if (!p1 || !p2) {
						return 0;
					}

					var dLat = (p2.lat() - p1.lat()) * Math.PI / 180,
						dLon = (p2.lng() - p1.lng()) * Math.PI / 180,
						a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) * Math.sin(dLon / 2) * Math.sin(dLon / 2);

					return 6371 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
				},

				addToClosestCluster_: function (marker) {
					var distance = 40000,
						clusterToAddTo = null,
						pos = marker.getPosition();

					for (var i = this.clusters_.length, center; i--;) {
						center = this.clusters_[i].getPosition();

						if (center) {
							var d = this.distanceBetweenPoints_(center, marker.getPosition());

							if (d < distance) {
								distance = d;
								clusterToAddTo = this.clusters_[i];
							}
						}
					}

					if (clusterToAddTo && clusterToAddTo.isMarkerInClusterBounds(marker)) {
						clusterToAddTo.addMarker(marker);
					} else {
						var cluster = new Cluster(this);

						cluster.addMarker(marker);
						this.clusters_.push(cluster);
					}
				},

				createClusters_: function () {
					if (!this.ready_) {
						return;
					}

					var mapBounds = new google.maps.LatLngBounds(
						_class.Map.getBounds().getSouthWest(),
						_class.Map.getBounds().getNorthEast()
					),
						bounds = this.getExtendedBounds(mapBounds);

					for (var i = this.markers_.length; i--;) {
						if (!this.markers_[i].isAdded && this.isMarkerInBounds_(this.markers_[i], bounds)) {
							this.addToClosestCluster_(this.markers_[i]);
						}
					}
				}
			};

			function Cluster(markerClusterer) {
				this.markerClusterer_ = markerClusterer;
				this.minClusterSize_ = markerClusterer.getMinClusterSize();
				this.averageCenter_ = markerClusterer.isAverageCenter();
				this.markers_ = [];
				this.ids = [];
				this.clusterIcon_ = new ClusterIcon(this, markerClusterer.set_.length);
			}

			Cluster.prototype = {
				constructor: Cluster,

				isMarkerAlreadyAdded: function (marker) {
					return this.markers_.indexOf(marker) >= 0;
				},

				addMarker: function (marker) {
					if (this.isMarkerAlreadyAdded(marker)) {
						return false;
					}

					if (!this.center_) {
						this.center_ = marker.getPosition();
						this.calculateBounds_();
					} else {
						if (this.averageCenter_) {
							var l = this.markers_.length + 1,
								lat = (this.center_.lat() * (l-1) + marker.getPosition().lat()) / l,
								lng = (this.center_.lng() * (l-1) + marker.getPosition().lng()) / l;

							this.center_ = new google.maps.LatLng(lat, lng);
							this.calculateBounds_();
						}
					}

					marker.isAdded = true;
					this.markers_.push(marker);
					this.ids.push(marker.id);

					var markersLength = this.markers_.length;

					if (markersLength < this.minClusterSize_ && marker.getMap() != _class.Map) {
						marker.setMap(_class.Map);
					}

					if (markersLength == this.minClusterSize_) {
						for (var i = 0; i < markersLength; i++) {
							this.markers_[i].setMap(null);
						}
					}

					if (markersLength >= this.minClusterSize_) {
						marker.setMap(null);
					}

					this.updateIcon();
					return true;
				},

				getMarkerClusterer: function () {
					return this.markerClusterer_;
				},

				getBounds: function () {
					var bounds = new google.maps.LatLngBounds(this.center_, this.center_),
						markers = this.getMarker();

					for (var i = markers.length; i--;) {
						bounds.extend(markers[i].getPosition());
					}

					return bounds;
				},

				getSize: function () {
					return this.markers_.length;
				},

				getMarker: function () {
					return this.markers_;
				},

				getPosition: function () {
					return this.center_;
				},

				calculateBounds_: function () {
					this.bounds_ = this.markerClusterer_.getExtendedBounds(new google.maps.LatLngBounds(this.center_, this.center_));
				},

				isMarkerInClusterBounds: function (marker) {
					return this.bounds_.contains(marker.getPosition());
				},

				updateIcon: function () {
					var zoom = _class.Map.getZoom(),
						mz = this.markerClusterer_.getMaxZoom();

					if (mz && zoom > mz) {
						for (var i = this.markers_.length; i--;) {
							this.markers_[i].setMap(_class.Map);
						}

						return;
					}

					if (this.markers_.length < this.minClusterSize_) {
						this.clusterIcon_.hide();
						return;
					}

					this.clusterIcon_.setCenter(this.center_);
					this.clusterIcon_.setSums(this.markerClusterer_.getCalculator()(this.markers_, this.markerClusterer_.set_.length));
					this.clusterIcon_.show();
				},

				setActive: function () {
					if (this.clusterIcon_.template_) {
						this.clusterIcon_.template_.addClass('bxmap-active');
					}
				},

				unsetActive: function () {
					if (this.clusterIcon_.template_) {
						this.clusterIcon_.template_.removeClass('bxmap-active');
					}
				},

				select: function () {
					this.clusterIcon_.triggerClusterClick();
				},

				remove: function () {
					if (this.clusterIcon_.template_) {
						this.clusterIcon_.template_.removeClass('bxmap-active');
					}

					this.clusterIcon_.remove();
					this.ids.length = 0;
					this.markers_.length = 0;
					delete this.ids;
					delete this.markers_;
				}
			};

			function ClusterIcon(cluster) {
				cluster.getMarkerClusterer().extend(ClusterIcon, google.maps.OverlayView);
				this.cluster_ = cluster;
				this.setMap(_class.Map);
			}

			ClusterIcon.prototype = {
				constructor: ClusterIcon,

				triggerClusterClick: function () {
					var markerClusterer = this.cluster_.getMarkerClusterer();

					google.maps.event.trigger(markerClusterer, 'clusterclick', this.cluster_);

					if (markerClusterer.isZoomOnClick()) {
						var bounds = this.cluster_.getBounds();

						if (bounds.getNorthEast().equals(bounds.getSouthWest())) {
							if (this.isActive()) {
								_class.setClusterView();
								wrapper.trigger('cluster:detected');
							} else {
								_class.setClusterView(this.cluster_);
								wrapper.trigger('cluster:detected', [this.cluster_.getMarker(), bounds]);
							}
						} else {
							_class.Map.fitBounds(bounds);
						}
					}
				},

				isActive: function () {
					if (this.template_) {
						return this.template_.hasClass('bxmap-active');
					}
				},

				onAdd: function () {
					var _class = this;

					this.template_ = this.cluster_.getMarkerClusterer().template_.clone(true);

					if (this.visible_) {
						var bounds = this.cluster_.getBounds();

						this.createCSS(this.getPosFromLatLng_(this.center_));
						this.template_
						.css({
							color: this.color_ ? this.color_ : '#fff'
						})
						.html(this.sums_.text);

						if (bounds.getNorthEast().equals(bounds.getSouthWest())) {
							this.template_.addClass('bxmap-cluster-group');
						}
					}

					$(this.getPanes().overlayMouseTarget).append(this.template_);

					google.maps.event.addDomListener(this.template_.get(0), 'click', function () {
						_class.triggerClusterClick();
					});
				},

				getPosFromLatLng_: function (latlng) {
					var pos = this.getProjection().fromLatLngToDivPixel(latlng),
						set = this.cluster_.markerClusterer_.set_[this.sums_.index - 1];

					pos.x += set.offset[0];
					pos.y += set.offset[1];

					return pos;
				},

				draw: function () {
					if (this.visible_) {
						var pos = this.getPosFromLatLng_(this.center_);

						this.template_.css({
							top: pos.y,
							left: pos.x
						});
					}
				},

				hide: function () {
					if (this.template_ && this.template_.length) {
						this.template_.addClass('bxmap-none');
					}

					this.visible_ = false;
				},

				show: function () {
					if (this.template_ && this.template_.length) {
						this.createCSS(this.getPosFromLatLng_(this.center_));
						this.template_.removeClass('bxmap-none');
					}

					if ($Map.activeCluster) {
						var ids = this.cluster_.ids,
							activeIDs = $Temp.getActiveIDs();

						for (var i = ids.length; i--;) {
							if (ids[i] in activeIDs) {
								_class.setClusterView(this.cluster_);
								break;
							}
						}
					}

					this.visible_ = true;
				},

				remove: function () {
					this.setMap(null);
				},

				onRemove: function () {
					if (this.template_ && this.template_.length) {
						this.hide();
						this.template_.remove();
						this.template_ = null;
					}
				},

				setSums: function (sums) {
					this.sums_ = sums;
					this.text_ = sums.text;
					this.index_ = sums.index;

					if (this.template_ && this.template_.length) {
						this.template_.html(sums.text);
					}
				},

				setCenter: function (center) {
					this.center_ = center;
				},

				createCSS: function (pos) {
					this.template_.get(0).className = this.template_.get(0).className.replace(/\s*\bbxmap-cluster-count-\d/, '');
					this.template_.addClass('bxmap-cluster-count-' + this.sums_.index).css({
						top: pos.y,
						left: pos.x
					});
				}
			};

			return _class;
		}(),
		$Params = function () {
			var dummyStyle = document.documentElement.style,
				transitionend = {
					'': 'transitionend',
					'webkit': 'webkitTransitionEnd',
					'Moz': 'transitionend',
					'O': 'otransitionend',
					'ms': 'MSTransitionEnd'
				},
				vendor = (function () {
					var vendors = 't,webkitT,MozT,msT,OT'.split(',');

					for (var i = 0; i < vendors.length; i++) {
						if (vendors[i] + 'ransform' in dummyStyle) {
							return vendors[i].slice(0, vendors[i].length - 1);
						}
					}

					return false;
				})(),
				touchPad = (/hp-tablet/gi).test(navigator.appVersion),
				hasTouch = 'ontouchstart' in window && !touchPad;

			function prefixStyle (style) {
				if (vendor === '') {
					return style;
				}

				style = style.charAt(0).toUpperCase() + style.substr(1);
				return vendor + style;
			}

			return {
				isAndroid: (/android/gi).test(navigator.appVersion),
				isIDevice: (/iphone|ipad/gi).test(navigator.appVersion),
				isTouchPad: touchPad,
				cssVendor: vendor ? '-' + vendor.toLowerCase() + '-' : '',
				has3d: prefixStyle('perspective') in dummyStyle,
				hasTouch: hasTouch,
				hasTransform: vendor !== false,
				hasTransitionEnd: prefixStyle('transition') in dummyStyle,
				transform: prefixStyle('transform'),
				transformOrigin: prefixStyle('transformOrigin'),
				transitionProperty: prefixStyle('transitionProperty'),
				transitionDuration: prefixStyle('transitionDuration'),
				transitionTimingFunction: prefixStyle('transitionTimingFunction'),
				transitionDelay: prefixStyle('transitionDelay'),

				altEvents: {
					resize: 'onorientationchange' in window ? 'orientationchange' : 'resize',
					start: hasTouch ? 'touchstart' : 'mousedown',
					move: hasTouch ? 'touchmove' : 'mousemove',
					end: hasTouch ? 'touchend' : 'mouseup',
					cancel: hasTouch ? 'touchcancel' : 'mouseup',
					transitionEnd: vendor !== false ? transitionend[vendor] : false
				},

				getTranslate: function(x, y) {
					if (this.has3d) {
						return 'translate3d(' + x + ', ' + y + ', 0)';
					} else {
						return 'translate(' + x + ', ' + y + ')';
					}
				}
			}
		}(),
		$Common = function () {
			var head = document.head || document.getElementsByTagName('head')[0],
				style = document.createElement('style'),
				styleSheet,
				dataType = [
					'String',
					'Number',
					'Boolean',
					'Array',
					'Function',
					'Date',
					'RegExp',
					'Error',
					'Symbol'/*,
					'Map',
					'Set',
					'WeakMap',
					'WeakSet',
					'ArrayBuffer',
					'DataView',
					'Int8Array',
					'Uint8Array',
					'Uint8ClampedArray',
					'Int16Array',
					'Uint16Array',
					'Int32Array',
					'Uint32Array',
					'Float32Array',
					'Float64Array'*/
				],
				/*compoundType = [
					{
						name: 'Intl',
						constructors: [
							'Collator',
							'DateTimeFormat',
							'NumberFormat'
						]
					},
					{
						name: 'SIMD',
						constructors: [
							'float32x4',
							'float64x2',
							'int8x16',
							'int16x8',
							'int32x4'
						]
					}
				],*/
				nodeType = [
					'Element',
					'Attribute',
					'Text Node',
					'CDATA Section',
					'Entity Reference',
					'Entity',
					'Processing Instruction',
					'Comment',
					'Document',
					'Document Type',
					'Document Fragment',
					'Notation'
				];

			head.appendChild(style);
			styleSheet = document.styleSheets[document.styleSheets.length - 1];

			var _class = {
				getType: function (target, requestType) {
					var name = getName(target);

					if (arguments.length == 2) {
						switch (_class.getType(requestType)) {
							case 'String':
								requestType = requestType.replace(/\s+/, '').split(',');
							case 'Array':
								return compareString(requestType);
							case 'Number':
								if (isNaN(target)) {
									return isNaN(requestType);
								}
							default:
								return target === requestType;
						}
					} else {
						return name;
					}

					function compareString (value) {
						for (var i = value.length; i--;) {
							try {
								if (name.toLowerCase() == value[i].toLowerCase()) {
									return true;
								}
							} catch (z) {
								//return false;
							}
						}

						return false;
					}

					function getName (e) {
						if (e === undefined || e === null) {
							return e;
						} else {
							var type = typeof e;

							if (type != 'object') {
								return _class.getName(type);
							} else {
								type = Object.prototype.toString.call(e);

								if (type == '[object Object]') {
									for (var i = 0; i < dataType.length; i++) {
										var constructor = window[dataType[i]];

										if (constructor && constructor.prototype && e instanceof constructor) {
											return dataType[i];
										}
									}

									/*for (var i = 0; i < compoundType.length; i++) {
										var compound = window[compoundType[i].name],
											constructors = compoundType[i].constructors;

										if (compound) {
											for (var j = 0; j < constructors.length; j++) {
												var constructor = compound[constructors[j]];

												if (constructor && constructor.prototype && e instanceof constructor) {
													return compoundType[i].name + '.' + constructors[j];
												}
											}
										}
									}*/

									return 'Object';
								} else {
									if (e instanceof Node && e.nodeType) {
										return nodeType[e.nodeType - 1];
									} else {
										type = type.match(/([\w\.]+)[^\w]*$/);

										if (type && dataType.indexOf(type[1]) >= 0) {
											return type[1];
										} else {
											return 'Unknown Type';
										}
									}
								}
							}
						}
					}
				},

				getLang: function () {
					return document.documentElement.getAttribute('lang') || 'ru';
				},

				getName: function (value) {
					return value.slice(0, 1).toUpperCase() + value.slice(1);
				},

				getMonth: function (number, change) {
					var lang = this.getLang(),
						monthName = date.monthesName[lang][number];

					if (change && lang == 'ru') {
						Object.keys(date.monthesRule).forEach(function (i) {
							monthName = monthName.replace(new RegExp(date.monthesRule[i][0] + '$'), date.monthesRule[i][1]);
						});
					}

					return monthName;
				},

				getDay: function (number, param) {
					var type = param && param == 1 ? 'shortDaysName' : 'fullDaysName',
						dayName = date[type][this.getLang()][number];

					if (param && param == 2) {
						switch (number) {
							case 2:
							case 4:
							case 5:
								Object.keys(date.dayRule).forEach(function (i) {
									dayName = dayName.replace(new RegExp(date.dayRule[i][0] + '$'), date.dayRule[i][1]);
								});

								dayName = dayName.toLowerCase();
								break;
						}
					}

					return dayName;
				},

				clone: function (currentObject, params) {
					var newObject = new currentObject.constructor();

					Object.keys(currentObject).forEach(function (i) {
						newObject[i] = currentObject[i];
					});

					Object.keys(params).forEach(function (i) {
						newObject[i] = params[i];
					});

					return newObject;
				},

				createElement: function (tag) {
					var set = [],
						name = tag.split('*');

					if (name[1]) {
						set.push(alternate[name[1]]);
						tag = 'bxmap';
					}

					var element = $('<' + tag + '>');

					for (var i = 1; i < arguments.length; i++) {
						if (typeof arguments[i] == 'string') {
							set = set.concat(arguments[i].split(/\s+/));
						} else {
							element.attr(arguments[i]);
						}
					}

					if (set.length) {
						element.addClass(set.join(' '));
					}

					return element;
				},

				createRule: function (selectors, rules, index) {
					if (!index && index != 0) {
						index = styleSheet.cssRules.length;
					}

					styleSheet.insertRule(selectors + '{' + rules + '}', index);
				},

				deleteRule: function (options) {
					if (!options) {
						$.each(styleSheet.cssRules, function (index) {
							_deleteRule(index);
						});
					} else {
						if (isFinite(options)) {
							options = parseInt(options);

							if (options < styleSheet.cssRules.length) {
								_deleteRule(parseInt(options));
							}
						} else {
							for (var i = 0; i < styleSheet.cssRules.length; i++) {
								if (this.clearText(options) == this.clearText(styleSheet.cssRules[i].selectorText)) {
									_deleteRule(i);
									i--;
								}
							}
						}
					}

					function _deleteRule (_index) {
						styleSheet.removeRule(_index);
					}
				},

				clearText: function (text) {
					return text.replace(/[\s'"]/g, '');
				},

				loadScript: function (options, callback) {
					var start = new Date(),
						delta,
						name,
						scriptSet = {
							id: options.id,
							count: 0,
							set: {}
						};

					if (this.getType(options.address, ['array', 'object'])) {
						Object.keys(options.address).forEach(function (i) {
							chainScript('set_' + i, options.address[i]);
						});
					} else if (this.getType(options.address, 'string')) {
						chainScript('single', options.address);
					} else {
						callback();
					}

					function chainScript (name, address) {
						var sync;

						if (options.id == 'lib' && /\biscroll\b/.test(address) && window['IScroll']) {
							callback();
						} else {
							if ($Data[options.listener]) {
								scriptSet.listener = scriptQueueListener;
								snapErrorListener(scriptSet.listener, true);
							}

							scriptSet.timing = setTimeout(
								checkScript.bind(scriptSet),
								$Data.responseTime
							);
							scriptSet.count++;
							scriptSet.set[name] = createScript(
								$Temp.getFullPath(address, 'libs'),
								options.id,
								name,
								options.errorName
							);
						}
					}

					function createScript (src, id, name, errorName) {
						var script = document.createElement('script');

						script.src = src;
						script.async = true;
						script.charset = 'utf-8';
						script.onload = script.onreadystatechange = process;
						script.onerror = error;
						head.insertBefore(script, head.lastChild);

						function process (e) {
							e = e || event;

							if (e.type === 'load' || (/loaded|complete/.test(script.readyState) && (!document.documentMode || document.documentMode < 9))) {
								script.onload = script.onreadystatechange = script.onerror = null;
								checkScriptQueue(id, name, errorName, true);
							}
						}

						function error (e) {
							script.onload = script.onreadystatechange = script.onerror = null;
							checkScriptQueue(id, name, errorName);
						}

						return src;
					}

					function checkScript () {
						checkLoad(this.id, !!this.stop && !this.count);
					}

					function checkLoad (id, error) {
						if (!scriptSet.done) {
							scriptSet.done = true;
							snapErrorListener(scriptSet.listener);
							clearTimeout(scriptSet.timing);

							if (options.timeout) {
								delta = $Data.loadTime - (new Date() - start);

								if (delta < 0) {
									callback(error);
								} else {
									setTimeout(function () {
										callback(error);
									}, delta);
								}
							} else {
								callback(error);
							}
						}
					}

					function scriptQueueListener (e) {
						try {
							e.preventDefault();
							//e.stopPropagation();
						} catch (z) {
							e.returnValue = false;
							//e.cancelBubble = true;
						}

						checkLoad(options.id, options.listenerErrorName);
					}

					function snapErrorListener (listener, action) {
						if (action) {
							window.addEventListener('error', listener);
						} else {
							window.removeEventListener('error', listener);
						}
					}

					function checkScriptQueue (id, name, errorName, load) {
						scriptSet.count--;

						if (!scriptSet.stop) {
							if (!load) {
								scriptSet.stop = true;
								checkLoad(id, errorName);
							} else {
								delete scriptSet.set[name];
							}

							if (!scriptSet.count) {
								checkLoad(id, null);
							}
						} else {
							delete scriptSet.set[name];
						}
					}
				},

				extend: function (options, list) {
					(list || Object.keys(options)).forEach(function (i) {
						if (options[i] !== undefined) {
							_extend(i);
						}

						delete options[i];
					});

					function _extend (i) {
						if ($Data[i]) {
							switch (i) {
								case 'icon':
								case 'path':
								case 'directionOptions':
									_class.extendObject($Data[i], options[i], true);
									break;
								default:
									switch ($Common.getType($Data[i])) {
										case 'Object':
										case 'Array':
											_class.extendObject($Data[i], options[i]);
											break;
										default:
											$Data[i] = options[i];
											break;
									}

									break;
							}
						} else {
							$Data[i] = options[i];
						}
					}
				},

				extendObject: function (defObject, userObject, status) {
					switch ($Common.getType(userObject)) {
						case undefined:
							break;
						case 'Array':
							switch ($Common.getType(defObject)) {
								case 'Array':
									if (!status) {
										userObject.forEach(function (value) {
											if (defObject.indexOf(value) < 0) {
												defObject.push(value);
											}
										});

										break;
									}
								default:
									defObject = userObject;
									break;
							}

							break;
						case 'Object':
							Object.keys(userObject).forEach(function (i) {
								switch ($Common.getType(userObject[i])) {
									case undefined:
										delete defObject[i];
										break;
									case 'Array':
									case 'Object':
										switch ($Common.getType(defObject[i])) {
											case 'Object':
												defObject[i] = _class.extendObject(defObject[i], userObject[i], status);
												break;
											default:
												defObject[i] = userObject[i];
												break;
										}

										break;
									default:
										defObject[i] = userObject[i];
										break;
								}
							});
							break;
						default:
							defObject = userObject;
							break;
					}

					return defObject;
				},

				pushState: function (options) {
					this.state('push', options);
					return this.getQuery();
				},

				replaceState: function (options) {
					this.state('replace', options);
					return this.getQuery();
				},

				state: function (type, options) {
					try {
						history[type + 'State'](
							options.state || options.query,
							options.title || document.title,
							this.getURL({
								url: options.href,
								replace: options.query,
								hash: !options.removeHash
							})
						);
					} catch (z) {}
				},

				getURLParts: function (url, hash) {
					url = url !== undefined ? url : location.href;

					var hash = url.split('#'),
						search = hash[0].split('?'),
						result = {
							hash: hash[1]
						};

					if (search[1]) {
						result.path = search[0];
						result.search = search[1];
					} else if (/[&,=]/.test(search[0])) {
						result.path = '';
						result.search = search[0];
					} else {
						result.path = search[0];
						result.search = '';
					}

					return result;
				},

				getURL: function (options) {
					options = options || {};

					var href = this.getURLParts(options.url),
						query;

					options.url = href.search;
					query = this.getQuery(options);

					for (var list = Object.keys(query), result = [], i = 0; i < list.length; i++) {
						result.push(list[i] + '=' + query[list[i]].join(','));
					}

					if (href.path && result.length) {
						href.path += '?';
					}

					result = href.path + result.join('&');

					if (href.hash && options.hash) {
						result += '#' + href.hash;
					}

					return result;
				},

				getQuery: function (options) {
					options = options || {};

					if (!options.query) {
						options.query = this.getURLParts(options.url).search;
					}

					var query = makeObject(options.query),
						add = makeObject(options.add),
						remove = makeObject(options.remove),
						replace = makeObject(options.replace);

					for (var list = Object.keys(remove), i = 0; i < list.length; i++) {
						var id = [list[i]];

						if (remove[id]) {
							for (var j = 0; j < remove[id].length; j++) {
								var value = remove[id][j];

								removeItem(add[id], value);
								removeItem(replace[id], value);
								removeItem(query[id], value);
							}
						} else {
							delete add[id];
							delete replace[id];
							delete query[id];
						}
					}

					for (var list = Object.keys(replace), i = 0; i < list.length; i++) {
						var id = [list[i]];

						if (replace[id].length) {
							query[id] = options.single ? replace[id][0] : replace[id];
						} else {
							delete query[id];
						}
					}

					for (var list = Object.keys(add), i = 0; i < list.length; i++) {
						var id = [list[i]];

						if (!query[id] || !query[id].length) {
							query[id] = this.mergeArray([], add[id]);
						} else {
							query[id] = this.mergeArray([], query[id], add[id]);
						}
					}

					if (options.name) {
						return query[options.name];
					} else {
						return query;
					}

					function removeItem (set, value) {
						if (set) {
							var index = set.indexOf(value);

							if (index >= 0) {
								set.splice(index, 1);
							}
						}
					}

					function makeObject (list) {
						if (list) {
							switch (_class.getType(list)) {
								case 'String':
									list = list.split(/\s*&\s*/);

									if (list.length == 1 && !list[0]) {
										list = list[0].split(',');

										if (list.length == 1 && !list[0]) {
											return {};
										}
									}
								case 'Array':
									for (var i = 0, j, object = {}; i < list.length; i++) {
										var pair = list[i].replace(/\s+/g, ''),
											index = pair.indexOf('=');

										if (index > 0) {
											object[pair.slice(0, index)] = getValue(pair.slice(index + 1));
										} else {
											object[pair] = [];
										}
									}

									return object;
								case 'Object':
									for (var keys = Object.keys(list), i = 0; i < keys.length; i++) {
										if (list[keys[i]]) {
											switch (_class.getType(list[keys[i]])) {
												case 'String':
													list[keys[i]] = getValue(list[keys[i]]);
													break;
												case 'Array':
													break;
												case 'Object':
													list[keys[i]] = Object.keys(list[keys[i]]);
													break;
												default:
													list[keys[i]] = [list[keys[i]].toString()];
													break;
											}
										} else {
											list[keys[i]] = [];
										}
									}

									return list;
								default:
									return {};
							}
						} else {
							return {};
						}

						function getValue (value) {
							if (value.match(/^\[(.+)\]$/)) {
								return [value];
							} else {
								return _class.getArray(value);
							}
						}
					}
				},

				getArrayQuery: function (options) {
					var query = this.getQuery(options);

					if (query) {
						if (query.cat) {
							query.cat = this.getArray(query.cat);
						}

						if (query.item) {
							query.item = this.getArray(query.item);
						}

						return query;
					}
				},

				mergeArray: function () {
					var result = this.getArray(arguments[0]);

					for (var i = 1; i < arguments.length; i++) {
						var list = this.getArray(arguments[i]);

						for (var j = 0; j < list.length; j++) {
							if (result.indexOf(list[j]) < 0) {
								result.push(list[j]);
							}
						}
					}

					return result;
				},

				getArray: function (object, separator) {
					switch (this.getType(object)) {
						case 'String':
							return object.replace(/^\s+/g, '').replace(/\s+$/g, '').split(/\s*,\s*/);
						case 'Array':
							return object.slice();
						case 'Object':
							return Object.keys(object);
						default:
							return [];
					}
				},

				values: function (object, separator) {
					switch (this.getType(object)) {
						case 'String':
							return object.split(separator || /\s*;\s*/);
						case 'Array':
							return object.slice();
						case 'Object':
							var values = [];

							for (var list = Object.keys(object), i = 0; i < list.length; i++) {
								values.push(object[list[i]]);
							}

							return values;
						default:
							return [];
					}
				},

				normalizeValue: function (value, type) {
					switch (type) {
						case 'phone':
							var result = value.match(/^([+-\d\s\(\)\[\]]+\d+)(.*)/),
								text;

							if (result) {
								value = result[1];
								text = result[2];
							}

							return {
								value: value,
								add: text
							};
						case 'url':
							return {
								value: value,
								text: value.replace(/^(https?:\/\/)?(www\.)?/, '').replace(/\/$/, '')
							};
						default:
							return {
								value: value
							};
					}
				}
			};

			return _class;
		}();

	return $Module;
}({
	mapType: 'yandex',
	loadTime: 500,
	responseTime: 10000,
	//listenMainScriptLoading: true,
	//listenMapScriptLoading: true,
	interfaceText: {
		error: 'РћС€РёР±РєР°',
		collapsePanel: 'РЎРІРµСЂРЅСѓС‚СЊ',
		catsTitle: 'РљР°С‚РµРіРѕСЂРёРё',
		subcatsTitle: 'РџРѕРґРєР°С‚РµРіРѕСЂРёРё',
		objectsTitle: 'РћР±СЉРµРєС‚С‹',
		popupTitle: 'РћР±СЉРµРєС‚С‹',
		groupCategoryName: 'Р‘РµР· РєР°С‚РµРіРѕСЂРёРё',
		clearField: 'РћС‡РёСЃС‚РёС‚СЊ РїРѕР»Рµ',
		placeHolder: 'РџРѕРёСЃРє. РќР°РїСЂРёРјРµСЂ, РђСЂР±Р°С‚',
		refreshMarkers: 'РћР±РЅРѕРІРёС‚СЊ РјР°СЂРєРµСЂС‹',
		clearCategories: 'РћС‚РјРµРЅРёС‚СЊ РІС‹Р±РѕСЂ',
		closeList: 'Р—Р°РєСЂС‹С‚СЊ',
		showList: 'РЎРїРёСЃРѕРє',
		back: 'Р’РµСЂРЅСѓС‚СЊСЃСЏ',
		showMarker: 'РџРѕРєР°Р·Р°С‚СЊ РЅР° РєР°СЂС‚Рµ',
		route: 'РњР°СЂС€СЂСѓС‚',
		walking: 'РџРµС€РєРѕРј',
		transit: 'РўСЂР°РЅСЃРїРѕСЂС‚',
		driving: 'РђРІС‚Рѕ',
		bicycling: 'Р’РµР»РѕСЃРёРїРµРґ',
		toWalk: 'Р?РґС‚Рё',
		toDrive: 'Р•С…Р°С‚СЊ',
		reverseDirection: 'РЎРјРµРЅРёС‚СЊ РЅР°РїСЂР°РІР»РµРЅРёРµ',
		createRoute: 'РџСЂРѕР»РѕР¶РёС‚СЊ',
		pointsTitle: 'РћР±СЉРµРєС‚С‹ РјР°СЂС€СЂСѓС‚Р°',
		currentPosition: 'РњРѕРµ С‚РµРєСѓС‰РµРµ РїРѕР»РѕР¶РµРЅРёРµ',
		from: 'РћС‚РєСѓРґР°',
		to: 'РљСѓРґР°',
		catAbstractName: 'Р“СЂСѓРїРїР°',
		directionTitle: 'РќР°СЃС‚СЂРѕР№РєР° РјР°СЂС€СЂСѓС‚Р°',
		multiObjects: 'РћР±СЉРµРєС‚С‹ РїРѕ Р°РґСЂРµСЃСѓ',
		popular: 'РџРѕРїСѓР»СЏСЂРЅС‹Рµ',
		showMap: 'РћС‚РєСЂС‹С‚СЊ РєР°СЂС‚Сѓ',
		showMapObject: 'РџРѕРєР°Р·Р°С‚СЊ РЅР° РєР°СЂС‚Рµ',
		closeMap: 'Р—Р°РєСЂС‹С‚СЊ РєР°СЂС‚Сѓ',
		showObjects: 'РџРѕРєР°Р·Р°С‚СЊ РІСЃРµ РѕР±СЉРµРєС‚С‹ РїРѕ Р°РґСЂРµСЃСѓ',
		showCurrentObject: 'Р’РµСЂРЅСѓС‚СЊСЃСЏ Рє РІС‹Р±СЂР°РЅРЅРѕРјСѓ РѕР±СЉРµРєС‚Сѓ',
		increaseZoom: 'РЈРІРµР»РёС‡РёС‚СЊ РјР°СЃС€С‚Р°Р±',
		decreaseZoom: 'РЈРјРµРЅСЊС€РёС‚СЊ РјР°СЃС€С‚Р°Р±',
		showFullScreen: 'Р Р°Р·РІРµСЂРЅСѓС‚СЊ РєР°СЂС‚Сѓ РЅР° РїРѕР»РЅС‹Р№ СЌРєСЂР°РЅ',
		choiceMarker: 'Р’С‹Р±СЂР°С‚СЊ РјР°СЂРєРµСЂ РЅР° РєР°СЂС‚Рµ',
		choiceText: 'РџРµСЂРµРєР»СЋС‡РёС‚СЊ РІС‹Р±РѕСЂ',
		regional: 'РћРїСЂРµРґРµР»СЏС‚СЊ СЂРµРіРёРѕРЅ'
	},
	routeMessages: {
		INVALID_REQUEST: 'РќРµРІРµСЂРЅС‹Р№ Р·Р°РїСЂРѕСЃ',
		ERROR: 'РќРµРІРѕР·РјРѕР¶РЅРѕ СЃРѕРµРґРёРЅРёС‚СЊСЃСЏ СЃ СЃРµСЂРІРµСЂРѕРј',
		MAX_WAYPOINTS_EXCEEDED: 'РЎР»РёС€РєРѕРј РјРЅРѕРіРѕ РїСЂРѕРјРµР¶СѓС‚РѕС‡РЅС‹С… С‚РѕС‡РµРє',
		NOT_FOUND: 'РќРµРІРѕР·РјРѕР¶РЅРѕ РѕРїСЂРµРґРµР»РёС‚СЊ РєРѕРѕСЂРґРёРЅР°С‚С‹ РїРѕ РІРІРµРґС‘РЅРЅРѕРјСѓ Р°РґСЂРµСЃСѓ',
		OK: 'РњР°СЂС€СЂСѓС‚ СѓСЃРїРµС€РЅРѕ РїРѕСЃС‚СЂРѕРµРЅ',
		OVER_QUERY_LIMIT: 'РџСЂРµРІС‹С€РµРЅ Р»РёРјРёС‚ РЅР° РєРѕР»РёС‡РµСЃС‚РІРѕ Р·Р°РїСЂРѕСЃРѕРІ',
		REQUEST_DENIED: 'РЎРµСЂРІРёСЃ РЅР° СЌС‚РѕР№ СЃС‚СЂР°РЅРёС†Рµ РЅРµРґРѕСЃС‚СѓРїРµРЅ',
		UNKNOWN_ERROR: 'РћС€РёР±РєР° РЅРµРёР·РІРµСЃС‚РЅРѕР№ РїСЂРёСЂРѕРґС‹',
		ZERO_RESULTS: 'РќРµРІРѕР·РјРѕР¶РЅРѕ РїСЂРѕР»РѕР¶РёС‚СЊ РјР°СЂС€СЂСѓС‚',
		wait: 'РћР¶РёРґР°РµС‚СЃСЏ РѕС‚РІРµС‚ РЅР° Р·Р°РїСЂРѕСЃ...',
		blocked: 'Р¤СѓРЅРєС†РёСЏ РѕРїСЂРµРґРµР»РµРЅРёСЏ РјРµСЃС‚РѕРїРѕР»РѕР¶РµРЅРёСЏ Р·Р°Р±Р»РѕРєРёСЂРѕРІР°РЅР°. Р’РІРµРґРёС‚Рµ Р°РґСЂРµСЃ РІСЂСѓС‡РЅСѓСЋ.',
		none: 'Р¤СѓРЅРєС†РёСЏ РѕРїСЂРµРґРµР»РµРЅРёСЏ РЅРµ РїРѕРґРґРµСЂР¶РёРІР°РµС‚СЃСЏ. Р’РІРµРґРёС‚Рµ Р°РґСЂРµСЃ РІСЂСѓС‡РЅСѓСЋ.'
	},
	parseMessages: {
		DEVICE_NOT_DEFINED: 'РќРµ СѓРєР°Р·Р°РЅ С‚РёРї СѓСЃС‚СЂРѕР№СЃС‚РІР°',
		UNKNOWN_DEFAULT_PATH: 'РќРµ СѓРєР°Р·Р°РЅС‹ РїСѓС‚Рё Рє С„Р°Р№Р»Р°Рј РјРѕРґСѓР»СЏ',
		PAGETYPE_NOT_DEFINED: 'РќРµ СѓРєР°Р·Р°РЅ С‚РёРї СЃС‚СЂР°РЅРёС†С‹',
		AJAX_NOT_DEFINED: 'РќРµ СѓРєР°Р·Р°РЅ РїСѓС‚СЊ РґР»СЏ Р·Р°РіСЂСѓР·РєРё РґР°РЅРЅС‹С…',
		MAP_PARAMETERS_NOT_DEFINED: 'РќРµ Р·Р°РґР°РЅС‹ РїР°СЂР°РјРµС‚СЂС‹ РєР°СЂС‚С‹',
		COMMON_SCRIPT_NOT_LOADED: 'РќРµ СѓРґР°Р»РѕСЃСЊ Р·Р°РіСЂСѓР·РёС‚СЊ РѕСЃРЅРѕРІРЅС‹Рµ СЃРєСЂРёРїС‚С‹',
		MAP_SCRIPT_NOT_LOADED: 'РќРµ СѓРґР°Р»РѕСЃСЊ Р·Р°РіСЂСѓР·РёС‚СЊ СЃРєСЂРёРїС‚С‹ РєР°СЂС‚С‹',
		MAP_DENIED: 'Р—Р°РїСЂРµС‚ РЅР° РёСЃРїРѕР»СЊР·РѕРІР°РЅРёРµ РєР°СЂС‚С‹ РґР»СЏ РїСЂРѕРєР»Р°РґРєРё РјР°СЂС€СЂСѓС‚Р°',
		NO_CATS: 'РќРµС‚ РЅРё РѕРґРЅРѕР№ РєР°С‚РµРіРѕСЂРёРё',
		NO_ITEMS: 'РќРµС‚ РЅРё РѕРґРЅРѕРіРѕ РѕР±СЉРµРєС‚Р°',
		INDEFINED_ERRORS: 'РќРµРѕРїСЂРµРґРµР»С‘РЅРЅР°СЏ РѕС€РёР±РєР°',
		ERRORS_COMMON_LOADING: 'РќРµРѕРїСЂРµРґРµР»С‘РЅРЅР°СЏ РѕС€РёР±РєР° РІРѕ РІСЂРµРјСЏ Р·Р°РіСЂСѓР·РєРё РѕСЃРЅРѕРІРЅС‹С… СЃРєСЂРёРїС‚РѕРІ',
		ERRORS_MAP_LOADING: 'РќРµРѕРїСЂРµРґРµР»С‘РЅРЅР°СЏ РѕС€РёР±РєР° РІРѕ РІСЂРµРјСЏ Р·Р°РіСЂСѓР·РєРё СЃРєСЂРёРїС‚РѕРІ РєР°СЂС‚С‹'
	},
	cluster: {
		gridSize: 32,
		anchor: [0, 0],
		icon: 'cluster.png',
		color: '#fff',
		set: [
			{size: 50},
			{size: 60},
			{size: 74},
			{size: 90}
		]
	},
	libs: [
		'iscroll.js'
	],
	mapScript: {
		google: '//maps.googleapis.com/maps/api/js?sensor=true&language=ru',
		yandex: '//api-maps.yandex.ru/2.1/?lang=ru_RU'
	}
},
{
	div: 'alt-block',
	ol: 'alt-ordered-list',
	ul: 'alt-unordered-list',
	li: 'alt-list-item',
	dl: 'alt-definition-list',
	dt: 'alt-definition-title',
	dd: 'alt-definition-description',
	span: 'alt-inline',
	ispan: 'alt-inline-block'
},
{
	monthesName: {
		ru: [
			'РЇРЅРІР°СЂСЊ',
			'Р¤РµРІСЂР°Р»СЊ',
			'РњР°СЂС‚',
			'РђРїСЂРµР»СЊ',
			'РњР°Р№',
			'Р?СЋРЅСЊ',
			'Р?СЋР»СЊ',
			'РђРІРіСѓСЃС‚',
			'РЎРµРЅС‚СЏР±СЂСЊ',
			'РћРєС‚СЏР±СЂСЊ',
			'РќРѕСЏР±СЂСЊ',
			'Р”РµРєР°Р±СЂСЊ'
		],
		en: [
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December'
		],
		fr: [
			'Janvier',
			'Fevrier',
			'Mars',
			'Avril',
			'Mai',
			'Juin',
			'Juillet',
			'Aout',
			'Septembre',
			'Octobre',
			'Novembre',
			'Decembre'
		]
	},
	fullDaysName: {
		ru: [
			'РџРѕРЅРµРґРµР»СЊРЅРёРє',
			'Р’С‚РѕСЂРЅРёРє',
			'РЎСЂРµРґР°',
			'Р§РµС‚РІРµСЂРі',
			'РџСЏС‚РЅРёС†Р°',
			'РЎСѓР±Р±РѕС‚Р°',
			'Р’РѕСЃРєСЂРµСЃРµРЅСЊРµ'
		],
		en: [
			'Monday',
			'Tuesday',
			'Wednesday',
			'Thursday',
			'Friday',
			'Saturday',
			'Sunday'
		],
		fr: [
			'Lundi',
			'Mardi',
			'Mercredi',
			'Jeudi',
			'Vendredi',
			'Samedi',
			'Dimanche'
		]
	},
	shortDaysName: {
		ru: [
			'РџРЅ',
			'Р’С‚',
			'РЎСЂ',
			'Р§С‚',
			'РџС‚',
			'РЎР±',
			'Р’СЃ'
		],
		en: [
			'Mon',
			'Tue',
			'Wed',
			'Thu',
			'Fri',
			'Sat',
			'Sun'
		],
		fr: [
			'Lun',
			'Mar',
			'Mer',
			'Jeu',
			'Ven',
			'Sam',
			'Dim'
		]
	},
	monthesRule: [
		['(СЊ|Р№)', 'СЏ'],
		['С‚', 'С‚Р°']
	],
	dayRule: [
		['Р°', 'Сѓ']
	]
});