/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/cart.js":
/*!******************************!*\
  !*** ./resources/js/cart.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "initCartDeleteButton": () => (/* binding */ initCartDeleteButton)
/* harmony export */ });
function initCartDeleteButton(actionUrl) {
  var cartDeleteBtns = document.querySelectorAll('.cartDeleteBtn');

  for (var index = 0; index < cartDeleteBtns.length; index++) {
    var cartDeleteBtn = cartDeleteBtns[index];
    cartDeleteBtn.addEventListener('click', function (e) {
      var btn = e.target;
      var dataId = btn.getAttribute('data-id');
      var formData = new FormData();
      formData.append("_method", 'DELETE');
      var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
      var csrfToken = csrfTokenMeta.content;
      formData.append("_token", csrfToken);
      formData.append("product_option_id", dataId);
      var request = new XMLHttpRequest();
      request.open("POST", actionUrl);

      request.onreadystatechange = function () {
        if (request.readyState === XMLHttpRequest.DONE && request.status === 200 && request.responseText === "success") {
          window.location.reload();
        }
      };

      request.send(formData);
    });
  }
}



/***/ }),

/***/ "./resources/js/imageUploader.js":
/*!***************************************!*\
  !*** ./resources/js/imageUploader.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var imageUploader = function imageUploader(containers) {
  var _iterator = _createForOfIteratorHelper(containers),
      _step;

  try {
    var _loop = function _loop() {
      var container = _step.value;
      var input = container.querySelector('input[type=file]');
      input.addEventListener('change', function (e) {
        readURL(e.target);
      });
      var img = container.querySelector('img');
      var oldSrc = img.getAttribute('src');

      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            img.setAttribute('src', e.target.result);
          };

          reader.readAsDataURL(input.files[0]);
        } else {
          img.setAttribute('src', oldSrc);
        }
      }
    };

    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      _loop();
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (imageUploader);

/***/ }),

/***/ "./resources/js/initProductOptionsTable.js":
/*!*************************************************!*\
  !*** ./resources/js/initProductOptionsTable.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _imageUploader__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./imageUploader */ "./resources/js/imageUploader.js");
function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }


var counter = 0;

var appendItem = function appendItem(item) {
  var currentData = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var newItem = item.cloneNode(true);
  newItem.style.display = '';
  var inputs = newItem.querySelectorAll('input');
  var objectName = "new_".concat(counter);

  if (currentData) {
    objectName = currentData.id;
  } else {
    counter++;
  }

  var _iterator = _createForOfIteratorHelper(inputs),
      _step;

  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var input = _step.value;
      input.disabled = false;
      var name = input.getAttribute('attr-name');
      input.setAttribute('name', "product_options[".concat(objectName, "][").concat(name, "]"));

      if (currentData) {
        var value = currentData[name];

        if (['text', 'number'].includes(input.type)) {
          input.value = value;
        }

        if (input.type == 'checkbox') {
          input.checked = value;
        }

        if (name == 'image') {
          input.closest('div').querySelector('img').src = value;
        }
      }
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }

  var deleteButton = newItem.querySelector('button');
  deleteButton.addEventListener('click', function (e) {
    var btn = e.target;
    var selector = btn.getAttribute('data-target');
    btn.closest(selector).outerHTML = '';
  });
  container.appendChild(newItem);
  var product_option_image_previews = newItem.querySelectorAll('.product_option_preview_image');
  (0,_imageUploader__WEBPACK_IMPORTED_MODULE_0__.default)(product_option_image_previews);
};

var initProductOptionsTable = function initProductOptionsTable(add_button, container, item, product_options_json) {
  var product_options = JSON.parse(product_options_json);

  var _iterator2 = _createForOfIteratorHelper(product_options),
      _step2;

  try {
    for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
      var product_option = _step2.value;
      appendItem(item, product_option);
    }
  } catch (err) {
    _iterator2.e(err);
  } finally {
    _iterator2.f();
  }

  add_button.addEventListener('click', function () {
    appendItem(item);
  });
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (initProductOptionsTable);

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		if(__webpack_module_cache__[moduleId]) {
/******/ 			return __webpack_module_cache__[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*******************************!*\
  !*** ./resources/js/tools.js ***!
  \*******************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _imageUploader__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./imageUploader */ "./resources/js/imageUploader.js");
/* harmony import */ var _initProductOptionsTable__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./initProductOptionsTable */ "./resources/js/initProductOptionsTable.js");
/* harmony import */ var _cart__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./cart */ "./resources/js/cart.js");



window.imageUploader = _imageUploader__WEBPACK_IMPORTED_MODULE_0__.default;
window.initProductOptionsTable = _initProductOptionsTable__WEBPACK_IMPORTED_MODULE_1__.default;
window.initCartDeleteButton = _cart__WEBPACK_IMPORTED_MODULE_2__.initCartDeleteButton;
})();

/******/ })()
;