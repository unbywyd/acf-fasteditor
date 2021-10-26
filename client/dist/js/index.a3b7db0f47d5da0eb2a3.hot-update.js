"use strict";
/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
self["webpackHotUpdateclient"]("index",{

/***/ "./source/js/index.js":
/*!****************************!*\
  !*** ./source/js/index.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var vue_dist_vue_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue/dist/vue.js */ \"./node_modules/vue/dist/vue.js\");\n/* harmony import */ var vue_dist_vue_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_dist_vue_js__WEBPACK_IMPORTED_MODULE_0__);\n\nvar App = new (vue_dist_vue_js__WEBPACK_IMPORTED_MODULE_0___default())({\n  data: function data() {\n    return {\n      activeTab: 1,\n      editMode: false,\n      hasForms: false,\n      loaded: false,\n      widgets: [],\n      pid: null\n    };\n  },\n  mounted: function mounted() {\n    var el = this.$refs['storage'];\n    this.hasForms = el.dataset.hasforms == 'true';\n    this.pid = el.dataset.pid;\n    this.$el.parentNode.setAttribute('dir', 'ltr');\n    this.searchWidgets();\n  },\n  computed: {\n    getIframeFormsUrl: function getIframeFormsUrl() {\n      return '/wtp-acf-fasteditor/' + this.pid + '/all';\n    }\n  },\n  methods: {\n    addWidget: function addWidget(el) {\n      if (el.classList.contains('acf_fasteditor_included')) {\n        return;\n      }\n\n      if (!el.dataset.pid) {\n        return;\n      }\n\n      var editLink = document.createElement('');\n      el.appendChild(editLink);\n      el.classList.add('acf_fasteditor_included');\n      var widget = {\n        id: '_' + Math.random().toString(36).substr(2, 9),\n        post_id: el.dataset.pid,\n        title: el.dataset.title ? el.dataset.title : 'No name',\n        field: el.dataset['acf-field'] ? el.dataset['acf-field'] : 'all',\n        el: el\n      };\n      this.widgets.push(widget);\n    },\n    removeWidget: function removeWidget(el) {\n      var found = this.widgets.find(function (el) {\n        return el.el == el;\n      });\n\n      if (found) {\n        this.widgets.splice(this.widgets.indexOf(found), 1);\n      }\n    },\n    searchWidgets: function searchWidgets() {\n      var _this = this;\n\n      var selector = '.mixcode_widget[data-pid], .acf-widget[data-pid], .acf_widget[data-pid]';\n      document.querySelectorAll(selector).forEach(function (el) {\n        _this.addWidget(el);\n      });\n    },\n    message_height: function message_height(data) {\n      this.$el.querySelector('#' + data.id).style.height = data.height + 'px';\n    },\n    widgetIframeURL: function widgetIframeURL(el) {\n      return '/wtp-acf-fasteditor/' + el.post_id + '/' + el.field;\n    },\n    listener: function listener(data) {\n      try {\n        data = JSON.parse(data.data);\n\n        if (data.wtp) {\n          data = data.wtp;\n        }\n\n        if (!this['message_' + data.event]) {\n          return;\n        }\n\n        this['message_' + data.event].call(this, data);\n      } catch (e) {}\n    },\n    onEditMode: function onEditMode() {\n      this.editMode = true;\n    },\n    onIframeLoad: function onIframeLoad(event) {\n      var id = '_' + Math.random().toString(36).substr(2, 9);\n      event.target.setAttribute('id', id);\n      event.target.contentWindow.postMessage(JSON.stringify({\n        wtp: {\n          event: 'init',\n          id: id\n        }\n      }), '*');\n    }\n  }\n}).$mount('#app_acf_fasteditor');\nwindow.addEventListener('message', App.listener);\n\n//# sourceURL=webpack://client/./source/js/index.js?");

/***/ })

},
/******/ function(__webpack_require__) { // webpackRuntimeModules
/******/ /* webpack/runtime/getFullHash */
/******/ (() => {
/******/ 	__webpack_require__.h = () => ("8005445526b7bb33c27d")
/******/ })();
/******/ 
/******/ }
);