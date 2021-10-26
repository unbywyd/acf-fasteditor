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

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var vue_dist_vue_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue/dist/vue.js */ \"./node_modules/vue/dist/vue.js\");\n/* harmony import */ var vue_dist_vue_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_dist_vue_js__WEBPACK_IMPORTED_MODULE_0__);\n\nvar App = new (vue_dist_vue_js__WEBPACK_IMPORTED_MODULE_0___default())({\n  data: function data() {\n    return {\n      activeTab: 1,\n      editMode: false,\n      hasForms: false,\n      loaded: false,\n      widgets: [],\n      pid: null\n    };\n  },\n  mounted: function mounted() {\n    var el = this.$refs['storage'];\n    this.hasForms = el.dataset.hasforms == 'true';\n    this.pid = el.dataset.pid;\n    this.$el.parentNode.setAttribute('dir', 'ltr');\n  },\n  computed: {\n    getIframeFormsUrl: function getIframeFormsUrl() {\n      return '/wtp-acf-fasteditor/' + this.pid + '/all';\n    }\n  },\n  methods: {\n    listener: function (_listener) {\n      function listener(_x) {\n        return _listener.apply(this, arguments);\n      }\n\n      listener.toString = function () {\n        return _listener.toString();\n      };\n\n      return listener;\n    }(function (data) {\n      console.log(listener);\n\n      try {\n        data = JSON.parse(data.data);\n      } catch (e) {}\n    }),\n    onEditMode: function onEditMode() {\n      this.editMode = true;\n    },\n    onIframeLoad: function onIframeLoad(event) {\n      console.log('Iframe loaded');\n      event.target.addEventListener('test', this.listener);\n      event.target.contentWindow.addEventListener('test', this.listener);\n    }\n  }\n}).$mount('#app_acf_fasteditor');\n\n//# sourceURL=webpack://client/./source/js/index.js?");

/***/ })

},
/******/ function(__webpack_require__) { // webpackRuntimeModules
/******/ /* webpack/runtime/getFullHash */
/******/ (() => {
/******/ 	__webpack_require__.h = () => ("e645939702f136330945")
/******/ })();
/******/ 
/******/ }
);