import { useSSRContext, ref, mergeProps, reactive, inject, resolveComponent, toRef, defineEmits, watch, onUpdated, onMounted, unref, provide, createApp } from "vue";
import axios from "axios";
import { ssrRenderAttrs, ssrRenderList, ssrRenderAttr, ssrInterpolate, ssrRenderClass, ssrRenderStyle, ssrRenderComponent, ssrIncludeBooleanAttr } from "vue/server-renderer";
import Swal$1 from "sweetalert2";
function getCurrencySymbolWithAmount(amount) {
  const currency = window.currencySymbol !== void 0 ? window.currencySymbol : { currencyPosition: "left", symbol: "" };
  if (currency.currencyPosition ?? true) {
    return currency.symbol + amount;
  }
  return amount + currency.symbol;
}
const _export_sfc = (sfc, props) => {
  const target = sfc.__vccOpts || sfc;
  for (const [key, val] of props) {
    target[key] = val;
  }
  return target;
};
const _sfc_main$j = {
  name: "RightCategory",
  setup() {
    const categories = ref([]);
    function test() {
      axios.get(window.appUrl + "/api/v1/category").then((response) => {
        categories.value = response.data.categories;
      }).catch((error) => {
      });
    }
    return {
      test,
      categories
    };
  },
  mounted() {
    this.test();
  }
};
function _sfc_ssrRender$d(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<div${ssrRenderAttrs(mergeProps({ class: "dashboard_posSystem__header__filter" }, _attrs))}><div class="dashboard_posSystem__header__filter__select radius-10"><span class="icon"><i class="material-symbols-outlined">filter_list</i></span><select class="select2_activation"><!--[-->`);
  ssrRenderList($setup.categories, (category) => {
    _push(`<option${ssrRenderAttr("value", category.id)}>${ssrInterpolate(category.name)}</option>`);
  });
  _push(`<!--]--></select></div></div>`);
}
const _sfc_setup$j = _sfc_main$j.setup;
_sfc_main$j.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/header/RightCategory.vue");
  return _sfc_setup$j ? _sfc_setup$j(props, ctx) : void 0;
};
const RightCategory = /* @__PURE__ */ _export_sfc(_sfc_main$j, [["ssrRender", _sfc_ssrRender$d]]);
const HeaderScan_vue_vue_type_style_index_0_scoped_4a9e8e61_lang = "";
const _sfc_main$i = {
  name: "HeaderScan",
  components: { RightCategory },
  setup(props, { emit }) {
    const searchType = ref("name");
    function searchProduct() {
      let key = document.querySelector("#search-sku").value;
      if (searchType.value === "sku") {
        emit("emitSearch", "sku=" + key);
      } else {
        emit("emitSearch", "name=" + key);
      }
    }
    function resetSearch() {
      emit("emitSearch", "reset=true");
      searchType.value = "name";
      document.querySelector("#search-sku").value = "";
    }
    function handleProductSearch() {
      document.getElementById("search-sku").focus();
      searchType.value = "sku";
    }
    return {
      searchProduct,
      resetSearch,
      handleProductSearch,
      searchType
    };
  }
};
function _sfc_ssrRender$c(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<div${ssrRenderAttrs(mergeProps({ class: "dashboard_posSystem__header__item__flex" }, _attrs))} data-v-4a9e8e61><div class="dashboard_posSystem__header__scan" data-v-4a9e8e61><div class="dashboard_posSystem__header__scan__flex tested" data-v-4a9e8e61><div class="dashboard_posSystem__header__scan__input" data-v-4a9e8e61><input id="search-sku" type="text" class="form--control radius-10"${ssrRenderAttr("placeholder", $setup.searchType === "sku" ? "Enter product SKU" : "Enter product name")} data-v-4a9e8e61><button class="search_icon tested" data-v-4a9e8e61><i class="las la-search" data-v-4a9e8e61></i></button></div><div class="dashboard_posSystem__header__scan__code gap-4" data-v-4a9e8e61><a href="#" class="${ssrRenderClass([$setup.searchType === "sku" ? "active" : "", "scan_btn scan_sku_btn radius-10 mx-1"])}" data-v-4a9e8e61><span class="scan_icon" data-v-4a9e8e61><i class="las la-barcode" data-v-4a9e8e61></i></span><span class="scan_title" data-v-4a9e8e61>Scan Code</span></a><a href="#" class="scan_btn reset_btn radius-10 mx-1" data-v-4a9e8e61><span class="scan_icon" data-v-4a9e8e61><i class="las la-undo-alt" data-v-4a9e8e61></i></span><span class="scan_title" data-v-4a9e8e61>Reset Search</span></a></div></div></div></div>`);
}
const _sfc_setup$i = _sfc_main$i.setup;
_sfc_main$i.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/header/HeaderScan.vue");
  return _sfc_setup$i ? _sfc_setup$i(props, ctx) : void 0;
};
const HeaderScan = /* @__PURE__ */ _export_sfc(_sfc_main$i, [["ssrRender", _sfc_ssrRender$c], ["__scopeId", "data-v-4a9e8e61"]]);
const HeaderCategories_vue_vue_type_style_index_0_lang = "";
const _sfc_main$h = {
  name: "HeaderCategories",
  setup(props, { emit }) {
    const search_text = ref("");
    const cateSlide = ref(false);
    const AllCategories = ref({});
    const activeMenus = reactive({ category: "", sub_category: "", child_category: "" });
    function fetchAllCategories() {
      axios.get(window.appUrl + "/api/v1/all-categories").then((response) => {
        AllCategories.value = response.data.data;
      }).catch((errors) => {
      });
    }
    function handleCategoryNavClick(event) {
      cateSlide.value = !cateSlide.value;
      let categorySlide = event.target.closest(".category__parent").querySelector(".category__menu");
      categorySlide.classList.toggle("show");
      if (categorySlide.classList.contains("show")) {
        document.querySelector(".category_overlay").classList.add("show");
      } else {
        document.querySelector(".category_overlay").classList.remove("show");
      }
    }
    function ToggleSubCategoryMenu(event, className = "subCategoryWarp") {
      var _a, _b;
      const currentEl = event.target;
      const type = currentEl.closest("li").getAttribute("data-category-type");
      let name = JSON.parse(currentEl.closest("li").getAttribute("data-category-data"));
      const oldActiveMenus = activeMenus.value;
      currentEl.closest("li.has-children").classList.toggle("down-arrow");
      if (type === "category") {
        activeMenus.value = { "category": name };
        removeAllActiveMenus("category", currentEl);
        removeAllActiveMenus("sub-category", currentEl);
        removeAllActiveMenus("child-category", currentEl);
      } else if (type === "sub_category") {
        activeMenus.value = { "category": oldActiveMenus.category, "sub_category": name };
        removeAllActiveMenus("sub-category", currentEl);
        removeAllActiveMenus("child-category", currentEl);
      }
      emit("emitCategorySelected", activeMenus);
      let parentElement = currentEl.parentElement;
      emit("emitSearch", parentElement.getAttribute("data-category"));
      (_b = (_a = parentElement.querySelector("." + className)) == null ? void 0 : _a.classList) == null ? void 0 : _b.toggle("d-none");
      currentEl.closest("li").classList.add("active");
      return true;
    }
    function removeAllActiveMenus(type, currentEl) {
      let allCategories = "";
      if (type === "category") {
        allCategories = document.querySelectorAll(".pos-categories li.category.active");
      } else if (type === "sub-category") {
        allCategories = document.querySelectorAll(".pos-categories li.sub-category.active");
      } else if (type === "child-category") {
        allCategories = document.querySelectorAll(".pos-categories li.child-category.active");
      }
      if (allCategories.length > 0) {
        allCategories.forEach(function(element, key) {
          element.classList.remove("active");
          element.classList.remove("down-arrow");
        });
        document.querySelectorAll(".subCategoryWarp").forEach(function(element, key) {
          if (type === "category") {
            let category_id = currentEl.closest("li.category").getAttribute("data-category").replace("category_id=", "");
            if (!element.classList.contains(`subCategoryWarp-${category_id}`)) {
              element.classList.add("d-none");
            }
          }
        });
      }
    }
    function handleChildCategoryClick(event) {
      const currentEl = event.target;
      let name = JSON.parse(currentEl.closest("li").getAttribute("data-category-data"));
      const oldActiveMenus = activeMenus.value;
      activeMenus.value = { "category": oldActiveMenus.category, "sub_category": name, "child_category": name };
      emit("emitCategorySelected", activeMenus);
      let parentElement = currentEl.parentElement;
      emit("emitSearch", parentElement.getAttribute("data-category"));
      removeAllActiveMenus("child-category");
      currentEl.closest("li").classList.add("active");
    }
    const fetchProductData = inject("fetchProductData");
    const selectedCategories = inject("selectedCategories");
    const searchCategory = () => {
      let categories = AllCategories.value;
      AllCategories.value = categories.filter((category) => {
        return category.name.toLowerCase().includes(search_text.value.toLowerCase());
      });
      if (search_text.value.length < 1) {
        fetchAllCategories();
        fetchProductData();
        selectedCategories.value = {};
      }
    };
    return {
      fetchAllCategories,
      cateSlide,
      AllCategories,
      handleCategoryNavClick,
      ToggleSubCategoryMenu,
      handleChildCategoryClick,
      activeMenus,
      search_text,
      searchCategory,
      fetchProductData
    };
  },
  mounted() {
    this.fetchAllCategories();
  }
};
function _sfc_ssrRender$b(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<!--[--><div class="dashboard_posSystem__category__nav category__nav radius-10"><p class="dashboard_posSystem__category__nav__main"><i class="las la-th-large"></i> All Categories </p></div><div class="dashboard_posSystem__category__menu category__menu radius-10 mt-3" style="${ssrRenderStyle($setup.cateSlide ? null : { display: "none" })}"><div class="dashboard_posSystem__category__menu__header"><div class="dashboard_posSystem__category__menu__header__input"><input${ssrRenderAttr("value", $setup.search_text)} type="text" class="form--control" placeholder="Find a Category"><button class="searchIcon"><i class="las la-search"></i></button></div></div><div class="dashboard_posSystem__category__menu__inner"><ul class="pos-categories"><!--[-->`);
  ssrRenderList($setup.AllCategories, (category) => {
    _push(`<li${ssrRenderAttr("data-category", "category_id=" + category.id)} class="${ssrRenderClass(category.sub_categories.length > 0 ? "has-children category " + (category.id === $setup.activeMenus.category.id ? "active" : "") : "")}"${ssrRenderAttr("data-category-data", JSON.stringify(category))}${ssrRenderAttr("data-category-type", "category")}><a href="#1">${ssrInterpolate(category.name)}</a>`);
    if (category.sub_categories ?? false) {
      _push(`<ul class="${ssrRenderClass(["subCategoryWarp-" + category.id, "submenu subCategoryWarp d-none"])}"><!--[-->`);
      ssrRenderList(category.sub_categories, (sub_category) => {
        _push(`<li${ssrRenderAttr("data-category", "sub_category_id=" + sub_category.id)} class="${ssrRenderClass(sub_category.child_categories.length > 0 ? "has-children sub-category" : "")}"${ssrRenderAttr("data-category-data", JSON.stringify(sub_category))}${ssrRenderAttr("data-category-type", "sub_category")}><a href="#1">${ssrInterpolate(sub_category.name)}</a>`);
        if (sub_category.child_categories.length > 0) {
          _push(`<ul class="submenu childCategoryWrap d-none"><!--[-->`);
          ssrRenderList(sub_category.child_categories, (child_category) => {
            _push(`<li${ssrRenderAttr("data-category", "child_category_id=" + child_category.id)}${ssrRenderAttr("data-category-data", JSON.stringify(child_category))}${ssrRenderAttr("data-category-type", "child_category")} class="${ssrRenderClass("child-category" + category.id === $setup.activeMenus.category.id ? "active" : "")}"><a href="#1">${ssrInterpolate(child_category.name)}</a></li>`);
          });
          _push(`<!--]--></ul>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</li>`);
      });
      _push(`<!--]--></ul>`);
    } else {
      _push(`<!---->`);
    }
    _push(`</li>`);
  });
  _push(`<!--]--></ul></div></div><!--]-->`);
}
const _sfc_setup$h = _sfc_main$h.setup;
_sfc_main$h.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/header/HeaderCategories.vue");
  return _sfc_setup$h ? _sfc_setup$h(props, ctx) : void 0;
};
const HeaderCategories = /* @__PURE__ */ _export_sfc(_sfc_main$h, [["ssrRender", _sfc_ssrRender$b]]);
const _sfc_main$g = {
  name: "TopHeader",
  components: { HeaderCategories, HeaderScan },
  beforeMount() {
  },
  mounted: () => {
    $(".select2_activation").select2();
  },
  setup(props, { emit }) {
    function emitSearch(data) {
      emit("emitSearch", data);
    }
    function handleCategorySelection(data) {
      emit("emitCategorySelected", data);
    }
    return {
      emitSearch,
      handleCategorySelection
    };
  }
};
function _sfc_ssrRender$a(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_HeaderCategories = resolveComponent("HeaderCategories");
  const _component_HeaderScan = resolveComponent("HeaderScan");
  _push(`<div${ssrRenderAttrs(mergeProps({ class: "dashboard_posSystem__header__flex" }, _attrs))}><div class="dashboard_posSystem__header__item"><div class="dashboard_posSystem__category category__parent">`);
  _push(ssrRenderComponent(_component_HeaderCategories, {
    onEmitCategorySelected: $setup.handleCategorySelection,
    onEmitSearch: $setup.emitSearch
  }, null, _parent));
  _push(`</div></div><div class="dashboard_posSystem__header__item scaning">`);
  _push(ssrRenderComponent(_component_HeaderScan, { onEmitSearch: $setup.emitSearch }, null, _parent));
  _push(`</div></div>`);
}
const _sfc_setup$g = _sfc_main$g.setup;
_sfc_main$g.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/header/Header.vue");
  return _sfc_setup$g ? _sfc_setup$g(props, ctx) : void 0;
};
const TopHeader = /* @__PURE__ */ _export_sfc(_sfc_main$g, [["ssrRender", _sfc_ssrRender$a]]);
const CartItems_vue_vue_type_style_index_0_scoped_9b57bf0a_lang = "";
const _sfc_main$f = {
  name: "CartItems",
  data() {
    return {
      log: console.log
    };
  },
  props: {
    loadCartItems: null,
    clearCart: null
  },
  emits: [],
  mounted() {
    this.getCartItems();
  },
  setup: (props, { emit }) => {
    const LoadCardStatus = toRef(props, "loadCartItems");
    const clearCartItem = toRef(props, "clearCart");
    defineEmits(["changeCartLoadState"]);
    const products = ref({});
    const testInput = ref(0);
    const cartItem = ref(0);
    ref(0);
    ref(0);
    const cartItemsLength = ref(0);
    const openModal = ref(false);
    const isExpanded = reactive({ expand: false, maxHeight: "300px" });
    function getCartItems() {
      axios.get(window.appUrl + "/shop-page/cart/ajax/get-cart-items").then((response) => {
        products.value = response.data;
        cartItemsLength.value = Object.keys(products.value).length;
        getTotalAmountWithTax(response.data);
        emit("cartProducts", products.value);
      });
    }
    function getTotalAmountWithTax(response) {
      let subTotal = 0;
      let tax = 0;
      let total = 0;
      let total_taxed_amount = 0;
      Object.keys(response).forEach(function(key) {
        var _a, _b, _c;
        subTotal += response[key].price * response[key].qty;
        tax += ((_b = (_a = response[key]) == null ? void 0 : _a.options) == null ? void 0 : _b.tax_options_sum_rate) ?? 0;
        let product_tax = ((_c = response[key].options) == null ? void 0 : _c.tax_options_sum_rate) ?? 0;
        let productWithQuantity = response[key].price * response[key].qty;
        let tax_amount = productWithQuantity / 100 * product_tax;
        total_taxed_amount += tax_amount;
        total += productWithQuantity + tax_amount;
      });
      emit("cartAmountWithTaxAmount", {
        tax,
        sub_total: subTotal,
        total,
        taxed_amount: total_taxed_amount
      });
    }
    function updateQuantity(rowId) {
      let quantity = document.querySelector("#modal-cart-item-quantity").value;
      axios.post(window.appUrl + "/shop-page/cart/ajax/update-quantity", {
        rowId,
        qty: quantity
      }).then((response) => {
        getCartItems();
        Swal$1.fire({
          position: "top-end",
          icon: "success",
          title: "Updated successfully",
          showConfirmButton: false,
          timer: 1e3
        });
        removeModalClass();
      }).catch((errors) => {
        prepare_errors(errors);
      });
    }
    function removeModalClass() {
      openModal.value = false;
    }
    function toggleModalClass(product) {
      cartItem.value = product;
      openModal.value = !openModal.value;
    }
    watch(LoadCardStatus, function(newValue, oldValue) {
      if (newValue) {
        emit("cartAdded");
        getCartItems();
      }
    }, { flush: "post" });
    function clearCartItems() {
      Swal$1.fire({
        title: "Are you sure?",
        text: "You won't be able to revert those!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, remove it!"
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(window.appUrl + "/shop-page/cart/ajax/clear-all-cart-items").then((response) => {
            getCartItems();
            Swal$1.fire(
              "Removed!",
              "All cart items has been cleared.",
              "success"
            );
          }).catch((errors) => {
            prepare_errors(errors);
          });
        }
      });
    }
    function removeCartItem(rowId) {
      Swal$1.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, remove it!"
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post(window.appUrl + "/shop-page/cart/ajax/remove", {
            rowId
          }).then((response) => {
            getCartItems();
            Swal$1.fire(
              "Removed!",
              "Selected item has been deleted.",
              "success"
            );
          }).catch((errors) => {
            prepare_errors(errors);
          });
        }
      });
    }
    const expandAll = () => {
      isExpanded.expand = !isExpanded.expand;
      isExpanded.maxHeight = "unset";
    };
    onUpdated(() => {
      if (clearCartItem.value) {
        products.value = {};
        cartItemsLength.value = 0;
        emit("cartCleared");
      }
    });
    return {
      products,
      cartItem,
      getCartItems,
      clearCartItems,
      testInput,
      LoadCardStatus,
      removeCartItem,
      removeModalClass,
      toggleModalClass,
      updateQuantity,
      cartItemsLength,
      openModal,
      isExpanded,
      expandAll
    };
  }
};
function _sfc_ssrRender$9(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<!--[-->`);
  if (_ctx.cartItemsLength > 0) {
    _push(`<div class="dashboard_posSystem__sidebar__item bg-white radius-10 padding-20" data-v-9b57bf0a><div class="dashboard_posSystem__sidebar__cart" data-v-9b57bf0a><div class="dashboard_posSystem__sidebar__cart__header" data-v-9b57bf0a><div class="dashboard_posSystem__sidebar__cart__header__flex" data-v-9b57bf0a><p class="dashboard_posSystem__sidebar__cart__header__para" data-v-9b57bf0a>Items in Cart</p><a href="#1" class="dashboard_posSystem__sidebar__cart__clear" data-bs-toggle="modal" data-bs-target="#clearCart" data-v-9b57bf0a><i class="las la-times" data-v-9b57bf0a></i> Clear Cart </a></div></div><div class="dashboard_posSystem__sidebar__cart__inner scrollWrap" style="${ssrRenderStyle([_ctx.isExpanded.expand ? { maxHeight: _ctx.isExpanded.maxHeight } : ""])}" data-v-9b57bf0a><div class="scrollWrap__inner" data-v-9b57bf0a><!--[-->`);
    ssrRenderList(_ctx.products, (product) => {
      _push(`<div class="dashboard_posSystem__sidebar__cart__item" data-v-9b57bf0a><div class="dashboard_posSystem__sidebar__cart__item__flex" data-v-9b57bf0a><div class="dashboard_posSystem__sidebar__cart__item__left" data-v-9b57bf0a><p class="dashboard_posSystem__sidebar__cart__item__para" data-v-9b57bf0a>${ssrInterpolate(product.name)}</p></div><div data-v-9b57bf0a>${ssrInterpolate(product.qty)}</div><div class="dashboard_posSystem__sidebar__cart__item__icon" data-v-9b57bf0a><a href="#0" class="icon" data-bs-toggle="modal" data-bs-target="#productEdit" data-v-9b57bf0a><i class="las la-pen-alt" data-v-9b57bf0a></i></a><a href="#0" class="icon remove" data-bs-toggle="modal" data-bs-target="#removeCart" data-v-9b57bf0a><i class="las la-times" data-v-9b57bf0a></i></a></div><p class="dashboard_posSystem__sidebar__cart__item__price" data-v-9b57bf0a>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(product.price))}</p></div></div>`);
    });
    _push(`<!--]--></div></div>`);
    if (_ctx.cartItemsLength < 1) {
      _push(`<div class="dashboard_posSystem__sidebar__cart__inner scrollWrap" data-v-9b57bf0a> Product cart is empty </div>`);
    } else {
      _push(`<!---->`);
    }
    _push(`<div class="dashboard_posSystem__sidebar__cart__footer" data-v-9b57bf0a><a href="#1" class="expand_btn" data-v-9b57bf0a>${ssrInterpolate(_ctx.isExpanded.expand ? "Collapse All" : "Expand All")} <i class="${ssrRenderClass(_ctx.isExpanded.expand ? "las la-angle-up" : "las la-angle-down")}" data-v-9b57bf0a></i></a></div></div></div>`);
  } else {
    _push(`<!---->`);
  }
  _push(`<div class="${ssrRenderClass(["popup_overlay", { popup_active: _ctx.openModal }])}" data-v-9b57bf0a></div><div class="${ssrRenderClass(["popup_fixed", "custom_modal_popup", { popup_active: _ctx.openModal }])}" data-v-9b57bf0a><div class="popup_contents" data-v-9b57bf0a><div class="popup_contents_header" data-v-9b57bf0a><div class="popup_contents_header__flex" data-v-9b57bf0a><span class="popup_contents_close popup_close" data-v-9b57bf0a><i class="las la-times" data-v-9b57bf0a></i></span><h5 class="popup_contents_title" data-v-9b57bf0a>Update cart item quantity</h5></div></div><hr data-v-9b57bf0a><div class="popup-contents-interview" data-v-9b57bf0a><div class="myJob-wrapper-single-contents" data-v-9b57bf0a><h6 class="d-flex justify-content-between" data-v-9b57bf0a><span class="popup_cart_left__title" data-v-9b57bf0a>${ssrInterpolate(_ctx.cartItem.name)}</span><span data-v-9b57bf0a>${ssrInterpolate(_ctx.cartItem.qty)}</span><span data-v-9b57bf0a>${ssrInterpolate(_ctx.cartItem.price)}</span></h6><h4 class="myJob-wrapper-single-title mt-3" data-v-9b57bf0a><input id="modal-cart-item-quantity" type="number" class="form-control"${ssrRenderAttr("value", _ctx.cartItem.qty)} data-v-9b57bf0a></h4></div></div><div class="popup_contents_btn" data-v-9b57bf0a><div class="btn-wrapper d-flex gap-3 justify-content-end" data-v-9b57bf0a><a href="#1" class="btn btn-danger popup_close" data-v-9b57bf0a> Cancel </a><a href="#1" class="btn btn-primary" data-v-9b57bf0a> Update item </a></div></div></div></div><!--]-->`);
}
const _sfc_setup$f = _sfc_main$f.setup;
_sfc_main$f.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/sidebar/CartItems.vue");
  return _sfc_setup$f ? _sfc_setup$f(props, ctx) : void 0;
};
const CartItems = /* @__PURE__ */ _export_sfc(_sfc_main$f, [["ssrRender", _sfc_ssrRender$9], ["__scopeId", "data-v-9b57bf0a"]]);
const _sfc_main$e = {
  name: "LeftSidebar"
};
function _sfc_ssrRender$8(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<div${ssrRenderAttrs(mergeProps({ class: "dashboard__left dashboard-left-content" }, _attrs))}><div class="dashboard__left__main"><div class="dashboard__left__close close-bars"><i class="fa-solid fa-times"></i></div><div class="dashboard__top"><div class="dashboard__top__logo"><a href="index_new.html"><img class="main_logo" src="assets/img/logo.png" alt="logo"><img class="iocn_view__logo" src="assets/img/logo_icon.png" alt="logo_icon"></a></div></div><div class="dashboard__bottom mt-5"><ul class="dashboard__bottom__list dashboard-list"><li class="dashboard__bottom__list__item"><a href="dashboard.html"><i class="material-symbols-outlined">dashboard</i> <span class="icon_title">Dashboard</span></a></li><li class="dashboard__bottom__list__item"><a href="inventory.html"><i class="material-symbols-outlined">inventory</i> <span class="icon_title">Inventory</span></a></li><li class="dashboard__bottom__list__item has-children show open active"><a href="#1"><i class="material-symbols-outlined">barcode_scanner</i> <span class="icon_title">Point of Sale</span></a><ul class="submenu"><li class="dashboard__bottom__list__item selected"><a href="pos_system.html">POS System</a></li><li class="dashboard__bottom__list__item"><a href="branches.html">Branches</a></li></ul></li><li class="dashboard__bottom__list__item"><a href="messages.html"><i class="material-symbols-outlined">mail</i> <span class="icon_title">Messages</span></a></li><li class="dashboard__bottom__list__item"><a href="orders.html"><i class="material-symbols-outlined">list_alt</i> <span class="icon_title">Orders</span> <span class="badge_notification"> 10 </span></a></li><li class="dashboard__bottom__list__item"><a href="customers.html"><i class="material-symbols-outlined">group</i> <span class="icon_title">Customers</span></a></li><li class="dashboard__bottom__list__item"><a href="marketing.html"><i class="material-symbols-outlined">campaign</i> <span class="icon_title">Marketing</span></a></li><li class="dashboard__bottom__list__item"><a href="support.html"><i class="material-symbols-outlined">help_center</i> <span class="icon_title">Support</span></a></li><li class="dashboard__bottom__list__item"><a href="invoice.html"><i class="material-symbols-outlined">receipt_long</i> <span class="icon_title">Invoice</span></a></li><li class="dashboard__bottom__list__item"><a href="integration.html"><i class="material-symbols-outlined">webhook</i> <span class="icon_title">Integrations</span></a></li><li class="dashboard__bottom__list__item"><a href="settings.html"><i class="material-symbols-outlined">settings</i> <span class="icon_title">Settings</span></a></li><li class="dashboard__bottom__list__item"><a href="#1"><i class="material-symbols-outlined">logout</i> <span class="icon_title">Log Out</span></a></li></ul></div></div></div>`);
}
const _sfc_setup$e = _sfc_main$e.setup;
_sfc_main$e.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/LeftSidebar.vue");
  return _sfc_setup$e ? _sfc_setup$e(props, ctx) : void 0;
};
const LeftSidebar = /* @__PURE__ */ _export_sfc(_sfc_main$e, [["ssrRender", _sfc_ssrRender$8]]);
const _sfc_main$d = {
  name: "Customer",
  setup(props, { emit }) {
    const search_text = ref("");
    const customer_tabs = ref("existing_customer");
    const customers = ref([]);
    const selectedCustomer = ref();
    const isSearchCustomer = ref(false);
    function handleCustomerSearch(event) {
      let input_values = event.target.value;
      if (input_values.length > 0) {
        searchCustomer(input_values);
        event.target.closest(".searchParent").querySelector(".searchWrap").classList.add("d-block");
        event.target.closest(".searchParent").querySelector(".searchWrap").classList.remove("d-none");
        document.querySelector(".body-overlay").classList.add("show");
      } else {
        event.target.closest(".searchParent").querySelector(".searchWrap").classList.remove("d-block");
        event.target.closest(".searchParent").querySelector(".searchWrap").classList.add("d-none");
        document.querySelector(".body-overlay").classList.remove("show");
      }
      selectedCustomer.value = null;
      emit("selectedCustomer", null);
    }
    function handleSelectCustomer(id) {
      axios.get(window.appUrl + "/admin-home/pos/customer/" + id).then((response) => {
        selectedCustomer.value = response.data;
        emit("selectedCustomer", response.data);
        document.querySelector("#selected_customer").value = selectedCustomer.value.id;
        document.querySelector("#existing_customer .searchParent .searchWrap").classList.remove("d-block");
        document.querySelector("#existing_customer .searchParent .searchWrap").classList.add("d-none");
        document.querySelector("#existing_customer .searchParent .single-input").classList.add("d-none");
        document.querySelector(".dashboard_posSystem__tabs .tabs").classList.add("d-none");
        document.querySelector(".body-overlay").classList.remove("show");
        document.querySelector(".dashboard_posSystem__tabs .tabs").classList.add("d-none");
      });
    }
    async function searchCustomer(searchData) {
      isSearchCustomer.value = true;
      await axios.get(window.appUrl + "/admin-home/pos/search-customer?email=" + searchData).then((response) => {
        customers.value = response.data;
        isSearchCustomer.value = false;
      });
    }
    function changeSelectedCustomer() {
      document.querySelector("#existing_customer .searchParent .single-input").classList.remove("d-none");
      document.querySelector(".dashboard_posSystem__tabs .tabs").classList.remove("d-none");
    }
    function handleTabs(event) {
      customer_tabs.value = event.target.getAttribute("data-tab");
      if (customer_tabs.value === "walk_customer") {
        document.querySelector("#walk_customer").classList.add("active");
        document.querySelector("#existing_customer").classList.remove("active");
        document.querySelector("#addCustomerButton").classList.add("d-none");
        selectedCustomer.value = null;
        emit("selectedCustomer", null);
        search_text.value = "";
      } else if (customer_tabs.value === "existing_customer") {
        document.querySelector("#walk_customer").classList.remove("active");
        document.querySelector("#existing_customer").classList.add("active");
        document.querySelector("#addCustomerButton").classList.remove("d-none");
      }
    }
    const activeTabs = (item_class) => {
      return customer_tabs.value === item_class ? "active" : "";
    };
    const showHideAddNewCustomer = () => {
      return search_text.value.length < 1;
    };
    return {
      handleCustomerSearch,
      handleSelectCustomer,
      changeSelectedCustomer,
      selectedCustomer,
      customers,
      handleTabs,
      isSearchCustomer,
      customer_tabs,
      activeTabs,
      showHideAddNewCustomer,
      search_text
    };
  }
};
function _sfc_ssrRender$7(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<!--[--><div class="dashboard_posSystem__sidebar__item bg-white radius-10 padding-20"><div class="dashboard_posSystem__tabs"><ul class="tabs"><li data-tab="existing_customer" class="${ssrRenderClass($setup.activeTabs("existing_customer"))}">Existing Customer</li><li data-tab="walk_customer" class="${ssrRenderClass($setup.activeTabs("walk_customer"))}">Walk in Customer</li></ul><div class="tab_content_item active mt-4" id="existing_customer"><div class="searchParent"><div class="single-input"><label for="findEmail" class="label_title">Find by Phone/Email</label><input type="text"${ssrRenderAttr("value", $setup.search_text)} class="form--control keyupInput radius-5" placeholder="Enter Phone/Email" id="findEmail"></div><div class="searchWrap scrollWrap d-none mt-3">`);
  if ($setup.customers.length > 0) {
    _push(`<div class="searchWrap__inner scrollWrap__inner"><!--[-->`);
    ssrRenderList($setup.customers, (customer) => {
      _push(`<div class="searchWrap__item"><div class="searchWrap__item__flex"><div class="searchWrap__left"><div class="searchWrap__left__flex"><div class="searchWrap__left__thumb"><img${ssrRenderAttr("src", customer.image)} alt="profile"></div><div class="searchWrap__left__contents"><h5 class="searchWrap__left__contents__title">${ssrInterpolate(customer.mobile)}</h5><p class="searchWrap__left__contents__subtitle">${ssrInterpolate(customer.name)} <small>(${ssrInterpolate(customer.username)})</small></p><small>${ssrInterpolate(customer.email)}</small></div></div></div><div class="searchWrap__right"><button class="searchWrap__right__loyal mt-2"> Select User </button></div></div></div>`);
    });
    _push(`<!--]--></div>`);
  } else {
    _push(`<!---->`);
  }
  if ($setup.isSearchCustomer || $setup.customers.length < 1) {
    _push(`<div class="scrollWrap__inner"><h5>${ssrInterpolate($setup.isSearchCustomer && $setup.customers.length < 1 ? `Searching customer...` : `No customer found`)}</h5></div>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</div></div></div><div class="tab_content_item mt-4" id="walk_customer"><div class="searchParent"><h5 class="text-center"> Ordering for walk in customer </h5></div></div></div>`);
  if ($setup.selectedCustomer) {
    _push(`<div class="card mt-3"><div class="card-body mt-0"><div class="d-flex justify-content-between align-items-center py-0"><h6 class="m-0">Selected Customer</h6><button class="btn btn-sm btn-info">Change</button></div><hr><div class="searchWrap__item__flex"><div class="searchWrap__left"><div class="searchWrap__left__flex"><div class="searchWrap__left__thumb"><img${ssrRenderAttr("src", $setup.selectedCustomer.image)} alt="profile"></div><div class="searchWrap__left__contents"><h5 class="searchWrap__left__contents__title">${ssrInterpolate($setup.selectedCustomer.phone)}</h5><p class="searchWrap__left__contents__subtitle">${ssrInterpolate($setup.selectedCustomer.name)}</p></div></div></div><div class="searchWrap__right"><h6 class="text-center">User from</h6><p>${ssrInterpolate($setup.selectedCustomer.date)}</p></div></div></div></div>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</div><div style="${ssrRenderStyle($setup.showHideAddNewCustomer() ? null : { display: "none" })}" id="addCustomerButton" class="dashboard_posSystem__sidebar__item bg-white radius-10 padding-20"><a href="#1" class="addCustomer" data-bs-toggle="modal" data-bs-target="#addCustomer"><i class="las la-user-alt"></i> Add a New Customer</a></div><!--]-->`);
}
const _sfc_setup$d = _sfc_main$d.setup;
_sfc_main$d.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/sidebar/Customer.vue");
  return _sfc_setup$d ? _sfc_setup$d(props, ctx) : void 0;
};
const Customer = /* @__PURE__ */ _export_sfc(_sfc_main$d, [["ssrRender", _sfc_ssrRender$7]]);
const Calculate_vue_vue_type_style_index_0_scoped_d47c63ef_lang = "";
const _sfc_main$c = {
  name: "Calculate",
  props: {
    data: null
  },
  setup(props, { emit }) {
    const taxAmount = ref(0);
    const totalAmount = ref(0);
    const tax = ref(props.data.tax);
    const sub_total = ref(props.data.sub_total);
    const customSubTotal = ref(props.data.total);
    const customTaxedAmount = ref(props.data.taxed_amount);
    const onlyTaxAmount = ref(customTaxedAmount.value);
    const couponAmount = ref(0);
    const couponCode = ref("");
    taxAmount.value = onlyTaxAmount.value;
    totalAmount.value = customSubTotal.value;
    emit("totalAmount", totalAmount);
    function handleCouponSubmission() {
      document.querySelector("#form_coupon").value = couponCode.value;
      axios.get(window.appUrl + "/checkout/coupon?coupon=" + couponCode.value).then((response) => {
        if (response.data.type === "success") {
          couponAmount.value = response.data.coupon_amount;
          let subTotal = sub_total.value - response.data.coupon_amount;
          onlyTaxAmount.value = subTotal / 100 * tax.value;
          taxAmount.value = onlyTaxAmount.value.toFixed(2);
          totalAmount.value = (subTotal + onlyTaxAmount.value).toFixed(2);
          emit("totalAmount", totalAmount.value);
          toastr.success("Coupon applied.");
        } else {
          couponAmount.value = 0;
          let subTotal = sub_total.value - 0;
          onlyTaxAmount.value = subTotal / 100 * tax.value;
          taxAmount.value = onlyTaxAmount.value.toFixed(2);
          totalAmount.value = (subTotal + onlyTaxAmount.value).toFixed(2);
          emit("totalAmount", totalAmount.value);
          toastr.error(response.data.msg);
        }
      });
    }
    function handleCancelOrder() {
      Swal$1.fire({
        title: "Are you sure?",
        text: "You won't be able to revert those!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, remove it!"
      }).then((result) => {
        if (result.isConfirmed) {
          axios.get(window.appUrl + "/shop-page/cart/ajax/clear-all-cart-items").then((response) => {
            emit("orderCanceled", true);
            Swal$1.fire(
              "Canceled!",
              "All cart items has been cleared.",
              "success"
            );
          }).catch((errors) => {
            prepare_errors(errors);
          });
        }
      });
    }
    watch(() => props.data, (newValue, oldValue) => {
      tax.value = newValue.tax;
      sub_total.value = newValue.sub_total;
      customSubTotal.value = newValue.total;
      customTaxedAmount.value = newValue.taxed_amount;
      if (customSubTotal.value > 0) {
        let subTotal = customSubTotal.value - couponAmount.value;
        onlyTaxAmount.value = customTaxedAmount.value;
        taxAmount.value = onlyTaxAmount.value;
        totalAmount.value = subTotal;
      } else {
        if (document.querySelector(".discountCoupon")) {
          document.querySelector(".discountCoupon").value = "";
        }
        couponAmount.value = 0;
        onlyTaxAmount.value = 0;
        taxAmount.value = 0;
        totalAmount.value = 0;
      }
      emit("totalAmount", totalAmount.value);
    });
    return {
      customSubTotal,
      customTaxedAmount,
      taxAmount,
      totalAmount,
      couponAmount,
      handleCouponSubmission,
      handleCancelOrder,
      couponCode
    };
  }
};
function _sfc_ssrRender$6(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  if ($props.data.sub_total > 0) {
    _push(`<div${ssrRenderAttrs(mergeProps({ class: "dashboard_posSystem__sidebar__item bg-white radius-10 padding-20" }, _attrs))} data-v-d47c63ef><div class="dashboard_posSystem__sidebar__estimate" data-v-d47c63ef><div class="dashboard_posSystem__sidebar__estimate__discount" data-v-d47c63ef><input type="text"${ssrRenderAttr("value", $setup.couponCode)} class="discountCoupon radius-5 discountCouponInput" placeholder="Enter a Coupon" data-v-d47c63ef><button class="btn btn-primary" data-v-d47c63ef><i class="las la-paper-plane" data-v-d47c63ef></i></button></div><div class="dashboard_posSystem__sidebar__estimate__count mt-4" data-v-d47c63ef><div class="dashboard_posSystem__sidebar__estimate__list" data-v-d47c63ef><div class="dashboard_posSystem__sidebar__estimate__list__item" data-v-d47c63ef><p class="title" data-v-d47c63ef>Subtotal</p><p class="price" data-v-d47c63ef>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount($props.data.sub_total))}</p></div><div class="dashboard_posSystem__sidebar__estimate__list__item" data-v-d47c63ef><p class="title" data-v-d47c63ef>Discount</p><p class="price" data-v-d47c63ef>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount($setup.couponAmount))}</p></div><div class="dashboard_posSystem__sidebar__estimate__list__item" data-v-d47c63ef><p class="title" data-v-d47c63ef>Vat</p><p class="price" data-v-d47c63ef>(+${ssrInterpolate($props.data.tax)}%) ${ssrInterpolate(_ctx.getCurrencySymbolWithAmount($setup.taxAmount.toFixed(2)))}</p></div></div><div class="dashboard_posSystem__sidebar__estimate__list" data-v-d47c63ef><div class="dashboard_posSystem__sidebar__estimate__list__item" data-v-d47c63ef><p class="title" data-v-d47c63ef><strong data-v-d47c63ef>Total</strong></p><p class="price" data-v-d47c63ef><strong data-v-d47c63ef>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount($setup.customSubTotal))}</strong></p></div></div></div><div class="dashboard_posSystem__sidebar__estimate__footer mt-4" data-v-d47c63ef><div class="dashboard_posSystem__sidebar__estimate__footer__btn btn_flex" data-v-d47c63ef>`);
    if ($setup.customSubTotal > 0) {
      _push(`<a href="#1" class="orderBtn_hold_order orderBtn radius-5" data-bs-toggle="modal" data-bs-target="#proceedHold" data-v-d47c63ef>Hold Order</a>`);
    } else {
      _push(`<!---->`);
    }
    _push(`<a href="#1" class="orderBtn_cancel orderBtn radius-5" data-v-d47c63ef>Cancel Order</a><a href="#1" class="orderBtn_proceed orderBtn radius-5" data-bs-toggle="modal" data-bs-target="#proceedCart" data-v-d47c63ef>Proceed to Order</a></div></div></div></div>`);
  } else {
    _push(`<!---->`);
  }
}
const _sfc_setup$c = _sfc_main$c.setup;
_sfc_main$c.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/sidebar/Calculate.vue");
  return _sfc_setup$c ? _sfc_setup$c(props, ctx) : void 0;
};
const Calculate = /* @__PURE__ */ _export_sfc(_sfc_main$c, [["ssrRender", _sfc_ssrRender$6], ["__scopeId", "data-v-d47c63ef"]]);
const _sfc_main$b = {
  name: "ProductCard",
  data() {
    return {
      symbol: this.currencySymbol.symbol,
      position: this.currencySymbol.position
    };
  },
  props: {
    loadCartItems: null,
    product: {}
  },
  setup(props, { emit }) {
    defineEmits(["cartAdded"]);
    ref({});
    toRef(props, "loadCartItems");
    function addToCartButton(event, product) {
      let currentEl = event.target;
      let csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
      let data = new FormData();
      data.append("product_id", product.prd_id);
      data.append("_token", csrf_token);
      data.append("quantity", 1);
      data.append("pid_id", "");
      data.append("product_variant", "");
      data.append("selected_size", "");
      data.append("selected_color", "");
      data.append("attribute", "");
      send_ajax_request("post", data, window.appUrl + "/shop-page/cart/ajax/add-to-cart", () => {
        currentEl.innerHTML = "Processing...";
        currentEl.setAttribute("disabled", true);
        currentEl.classList.add("disabled");
      }, (data2) => {
        emit("cartAdded");
        Swal$1.fire({
          position: "top-end",
          icon: "success",
          title: "Item added successfully",
          showConfirmButton: false,
          timer: 1500
        });
        currentEl.innerHTML = "Add to cart";
        currentEl.removeAttribute("disabled");
        currentEl.classList.remove("disabled");
      }, (errors) => {
        prepare_errors(errors);
        currentEl.innerHTML = "Add to cart";
        currentEl.removeAttribute("disabled");
        currentEl.classList.remove("disabled");
      });
    }
    function loadProductDetails(event, product) {
      let currentEl = event.target;
      currentEl.innerText = "Opening...";
      currentEl.setAttribute("disabled", true);
      axios.get(window.appUrl + "/api/v1/product/" + product.prd_id).then((response) => {
        currentEl.innerText = "View Details";
        currentEl.removeAttribute("disabled");
        document.querySelector(".interview-popup").classList.toggle("popup-active");
        document.querySelector(".popup-overlay").classList.toggle("popup-active");
        emit("loadProductView", response.data);
        let available_options = document.querySelectorAll(".value-input-area");
        available_options.forEach(function(element, key) {
          element.querySelector("li.active").classList.remove("active");
          element.parentElement.parentElement.querySelector("input[type=text]").value = "";
          element.parentElement.parentElement.querySelector("input[type=hidden]").value = "";
        });
      }).catch((errors) => {
        currentEl.innerText = "View Details";
        currentEl.removeAttribute("disabled");
        prepare_errors(errors);
      });
    }
    return {
      addToCartButton,
      loadProductDetails
    };
  }
};
function _sfc_ssrRender$5(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  _push(`<!--[--><div class="dashboard_posSystem__item__thumb radius-10"><div class="dashboard_posSystem__item__thumb__main"><img${ssrRenderAttr("src", $props.product.img_url)} alt=""></div></div><div class="dashboard_posSystem__item__contents mt-2"><h5 class="dashboard_posSystem__item__title">${ssrInterpolate($props.product.title)}</h5><span class="dashboard_posSystem__item__price mt-1">${ssrInterpolate(_ctx.getCurrencySymbolWithAmount($props.product.discount_price ?? $props.product.price))}</span><div class="dashboard_posSystem__item__btn btn_flex mt-2">`);
  if (!$props.product.is_cart_able) {
    _push(`<button class="posBtn_details posBtn radius-5">View Details</button>`);
  } else {
    _push(`<!---->`);
  }
  if ($props.product.is_cart_able) {
    _push(`<button class="posBtn_cart posBtn radius-5"${ssrRenderAttr("data-product-id", $props.product.prd_id)}> Add to Cart </button>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</div></div><!--]-->`);
}
const _sfc_setup$b = _sfc_main$b.setup;
_sfc_main$b.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/products/ProductCard.vue");
  return _sfc_setup$b ? _sfc_setup$b(props, ctx) : void 0;
};
const ProductCard = /* @__PURE__ */ _export_sfc(_sfc_main$b, [["ssrRender", _sfc_ssrRender$5]]);
function MD5(d) {
  var r = M(V(Y(X(d), 8 * d.length)));
  return r.toLowerCase();
}
function M(d) {
  for (var _, m = "0123456789ABCDEF", f = "", r = 0; r < d.length; r++)
    _ = d.charCodeAt(r), f += m.charAt(_ >>> 4 & 15) + m.charAt(15 & _);
  return f;
}
function X(d) {
  for (var _ = Array(d.length >> 2), m = 0; m < _.length; m++)
    _[m] = 0;
  for (m = 0; m < 8 * d.length; m += 8)
    _[m >> 5] |= (255 & d.charCodeAt(m / 8)) << m % 32;
  return _;
}
function V(d) {
  for (var _ = "", m = 0; m < 32 * d.length; m += 8)
    _ += String.fromCharCode(d[m >> 5] >>> m % 32 & 255);
  return _;
}
function Y(d, _) {
  d[_ >> 5] |= 128 << _ % 32, d[14 + (_ + 64 >>> 9 << 4)] = _;
  for (var m = 1732584193, f = -271733879, r = -1732584194, i = 271733878, n = 0; n < d.length; n += 16) {
    var h = m, t = f, g = r, e = i;
    f = md5_ii(f = md5_ii(f = md5_ii(f = md5_ii(f = md5_hh(f = md5_hh(f = md5_hh(f = md5_hh(f = md5_gg(f = md5_gg(f = md5_gg(f = md5_gg(f = md5_ff(f = md5_ff(f = md5_ff(f = md5_ff(f, r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 0], 7, -680876936), f, r, d[n + 1], 12, -389564586), m, f, d[n + 2], 17, 606105819), i, m, d[n + 3], 22, -1044525330), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 4], 7, -176418897), f, r, d[n + 5], 12, 1200080426), m, f, d[n + 6], 17, -1473231341), i, m, d[n + 7], 22, -45705983), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 8], 7, 1770035416), f, r, d[n + 9], 12, -1958414417), m, f, d[n + 10], 17, -42063), i, m, d[n + 11], 22, -1990404162), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 12], 7, 1804603682), f, r, d[n + 13], 12, -40341101), m, f, d[n + 14], 17, -1502002290), i, m, d[n + 15], 22, 1236535329), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 1], 5, -165796510), f, r, d[n + 6], 9, -1069501632), m, f, d[n + 11], 14, 643717713), i, m, d[n + 0], 20, -373897302), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 5], 5, -701558691), f, r, d[n + 10], 9, 38016083), m, f, d[n + 15], 14, -660478335), i, m, d[n + 4], 20, -405537848), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 9], 5, 568446438), f, r, d[n + 14], 9, -1019803690), m, f, d[n + 3], 14, -187363961), i, m, d[n + 8], 20, 1163531501), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 13], 5, -1444681467), f, r, d[n + 2], 9, -51403784), m, f, d[n + 7], 14, 1735328473), i, m, d[n + 12], 20, -1926607734), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 5], 4, -378558), f, r, d[n + 8], 11, -2022574463), m, f, d[n + 11], 16, 1839030562), i, m, d[n + 14], 23, -35309556), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 1], 4, -1530992060), f, r, d[n + 4], 11, 1272893353), m, f, d[n + 7], 16, -155497632), i, m, d[n + 10], 23, -1094730640), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 13], 4, 681279174), f, r, d[n + 0], 11, -358537222), m, f, d[n + 3], 16, -722521979), i, m, d[n + 6], 23, 76029189), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 9], 4, -640364487), f, r, d[n + 12], 11, -421815835), m, f, d[n + 15], 16, 530742520), i, m, d[n + 2], 23, -995338651), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 0], 6, -198630844), f, r, d[n + 7], 10, 1126891415), m, f, d[n + 14], 15, -1416354905), i, m, d[n + 5], 21, -57434055), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 12], 6, 1700485571), f, r, d[n + 3], 10, -1894986606), m, f, d[n + 10], 15, -1051523), i, m, d[n + 1], 21, -2054922799), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 8], 6, 1873313359), f, r, d[n + 15], 10, -30611744), m, f, d[n + 6], 15, -1560198380), i, m, d[n + 13], 21, 1309151649), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 4], 6, -145523070), f, r, d[n + 11], 10, -1120210379), m, f, d[n + 2], 15, 718787259), i, m, d[n + 9], 21, -343485551), m = safe_add(m, h), f = safe_add(f, t), r = safe_add(r, g), i = safe_add(i, e);
  }
  return Array(m, f, r, i);
}
function md5_cmn(d, _, m, f, r, i) {
  return safe_add(bit_rol(safe_add(safe_add(_, d), safe_add(f, i)), r), m);
}
function md5_ff(d, _, m, f, r, i, n) {
  return md5_cmn(_ & m | ~_ & f, d, _, r, i, n);
}
function md5_gg(d, _, m, f, r, i, n) {
  return md5_cmn(_ & f | m & ~f, d, _, r, i, n);
}
function md5_hh(d, _, m, f, r, i, n) {
  return md5_cmn(_ ^ m ^ f, d, _, r, i, n);
}
function md5_ii(d, _, m, f, r, i, n) {
  return md5_cmn(m ^ (_ | ~f), d, _, r, i, n);
}
function safe_add(d, _) {
  var m = (65535 & d) + (65535 & _);
  return (d >> 16) + (_ >> 16) + (m >> 16) << 16 | 65535 & m;
}
function bit_rol(d, _) {
  return d << _ | d >>> 32 - _;
}
const ProductModal_vue_vue_type_style_index_0_scoped_0a9a5810_lang = "";
const _sfc_main$a = {
  name: "product-modal",
  props: {
    productDetails: false
  },
  mounted() {
  },
  setup: (props, { emit }) => {
    var _a, _b;
    const product = toRef(props, "productDetails");
    const productPrice = ref((_a = product.value.product) == null ? void 0 : _a.sale_price);
    const productImage = ref((_b = product.value.product) == null ? void 0 : _b.image);
    const variantStock = ref(0);
    const outOfStock = ref(0);
    const productQuantity = ref(1);
    watch(() => props.productDetails, (newValue, oldValue) => {
      var _a2, _b2;
      productPrice.value = (_a2 = newValue.product) == null ? void 0 : _a2.sale_price;
      productImage.value = (_b2 = newValue.product) == null ? void 0 : _b2.image;
    });
    function cart_view_selected_options() {
      let selected_options = {};
      let available_options = document.querySelectorAll(".value-input-area");
      available_options.forEach(function(element, key) {
        var _a2;
        let selected_option = element.querySelector("li.active");
        let type = (_a2 = selected_option == null ? void 0 : selected_option.closest(".size-lists")) == null ? void 0 : _a2.getAttribute("data-type");
        let value = selected_option == null ? void 0 : selected_option.getAttribute("data-display-value");
        if (type && value) {
          selected_options[type] = value;
        }
      });
      let ordered_data = {};
      let selected_options_keys = Object.keys(selected_options).sort();
      selected_options_keys.map(function(e) {
        ordered_data[e] = String(selected_options[e]);
      });
      return ordered_data;
    }
    function getAttributesForCart() {
      let selected_options = cart_view_selected_options();
      let hashed_key = getSelectionHash(selected_options);
      if (product.value.additional_info_store[hashed_key]) {
        return product.value.additional_info_store[hashed_key]["pid_id"];
      }
      if (Object.keys(selected_options).length) {
        toastr.error('{{ __("Attribute not available") }}');
      }
      return "";
    }
    function variantProductAddToCart(product2) {
      var _a2, _b2;
      let selected_size = ((_a2 = document.querySelector("#selected_size")) == null ? void 0 : _a2.value) ?? null;
      let selected_color = ((_b2 = document.querySelector("#selected_color")) == null ? void 0 : _b2.value) ?? null;
      let pid_id = getAttributesForCart();
      let quantity = Number(document.querySelector(".product__quantity__input").value);
      if (validateSelectedAttributes()) {
        let csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        let data = new FormData();
        data.append("product_id", product2.id);
        data.append("_token", csrf_token);
        data.append("quantity", quantity);
        data.append("pid_id", pid_id);
        data.append("product_variant", pid_id);
        data.append("selected_size", selected_size);
        data.append("selected_color", selected_color);
        data.append("attribute", null);
        send_ajax_request("post", data, window.appUrl + "/shop-page/cart/ajax/add-to-cart", () => {
        }, (data2) => {
          emit("cartAdded");
          if (data2.type ?? false) {
            if (data2.type === "error") {
              Swal$1.fire({
                position: "top-end",
                icon: "error",
                title: data2.msg ?? "",
                showConfirmButton: false,
                timer: 1500
              });
              return "";
            }
          }
          Swal$1.fire({
            position: "top-end",
            icon: "success",
            title: "Item added successfully",
            showConfirmButton: false,
            timer: 1500
          });
        }, (errors) => {
          prepare_errors(errors);
        });
      } else {
        toastr.error("Select all attribute to proceed");
      }
    }
    function childCategoryString(childCategories) {
      let categories = "";
      for (let childCat of childCategories) {
        categories += childCat.name + " , ";
      }
      categories = categories.substring(0, categories.length - 2);
      return categories;
    }
    function closeModal() {
      document.querySelector(".popup-overlay").classList.remove("popup-active");
      document.querySelector(".interview-popup").classList.remove("popup-active");
    }
    function handleSizeListClick(event) {
      var _a2, _b2;
      let el = event.target;
      let value = el.getAttribute("data-display-value");
      let parentWrap = el.parentElement.parentElement;
      (_a2 = el.previousElementSibling) == null ? void 0 : _a2.classList.remove("active");
      el.classList.add("active");
      (_b2 = el.nextElementSibling) == null ? void 0 : _b2.classList.remove("active");
      parentWrap.querySelector("input[type=text]").value = value;
      parentWrap.querySelector("input[type=hidden]").value = el.getAttribute("data-value");
      productDetailSelectedAttributeSearch(el);
    }
    function validateSelectedAttributes() {
      let selected_options = cart_view_selected_options();
      let hashed_key = getSelectionHash(selected_options);
      if (product.value.product_inventory_set.length) {
        if (!Object.keys(selected_options).length) {
          return false;
        }
        if (!product.value.additional_info_store[hashed_key]) {
          return false;
        }
        return !!product.value.additional_info_store[hashed_key]["pid_id"];
      }
      return true;
    }
    function productDetailSelectedAttributeSearch(selected_item) {
      let availableOptions = document.querySelectorAll(".size-lists li");
      availableOptions.forEach(function(value, key) {
        availableOptions[key].classList.add("disabled-option");
      });
      let selected_options = {};
      let available_options = document.querySelectorAll(".value-input-area");
      available_options.forEach(function(element, key) {
        var _a2;
        let selected_option = element.querySelector("li.active");
        let type = (_a2 = selected_option == null ? void 0 : selected_option.closest(".size-lists")) == null ? void 0 : _a2.getAttribute("data-type");
        let value = selected_option == null ? void 0 : selected_option.getAttribute("data-display-value");
        if (type && value) {
          selected_options[type] = value;
        }
      });
      syncImage(view_selected_options(selected_options));
      syncPrice(view_selected_options(selected_options));
      syncStock(view_selected_options(selected_options));
      let selected_attributes_by_type = {};
      product.value.product_inventory_set.map(function(arr) {
        let matched = true;
        Object.keys(selected_options).map(function(type) {
          if (arr[type] != selected_options[type]) {
            matched = false;
          }
        });
        if (matched) {
          Object.keys(arr).map(function(type) {
            if (!selected_attributes_by_type[type]) {
              selected_attributes_by_type[type] = [];
            }
            if (selected_attributes_by_type[type].indexOf(arr[type]) <= -1) {
              selected_attributes_by_type[type].push(arr[type]);
            }
          });
        }
      });
      if (Object.keys(selected_attributes_by_type).length == 0) {
        let activeElements = document.querySelectorAll(".size-lists li.active");
        let disabledOptions = document.querySelectorAll(".size-lists li.disabled-option");
        activeElements.forEach(function(value, key) {
          activeElements[key].classList.remove("active");
          let sizeItem = activeElements[key].parentElement.parentElement;
          sizeItem.querySelector("input[type=hidden]").value = "";
          sizeItem.querySelector("input[type=text]").value = "";
        });
        disabledOptions.forEach(function(value, key) {
          disabledOptions[key].classList.remove("disabled-option");
        });
        let el = selected_item;
        el.getAttribute("data-displayValue");
        el.classList.add("active");
        productDetailSelectedAttributeSearch();
      }
      Object.keys(selected_attributes_by_type).map(function(type) {
        selected_attributes_by_type[type].map(function(value) {
          let available_buttons = $('.size-lists[data-type="' + type + '"] li[data-display-value="' + value + '"]');
          available_buttons.map(function(key, el) {
            $(el).removeClass("disabled-option");
          });
        });
      });
    }
    function syncImage(selected_options) {
      let hashed_key = getSelectionHash(selected_options);
      let product_image_el = document.querySelector("#product-image-field");
      let img_original_src = product_image_el.getAttribute("data-src");
      if (product.value.additional_info_store[hashed_key]) {
        let attribute_image = product.value.additional_info_store[hashed_key].image;
        if (attribute_image) {
          productImage.value = attribute_image;
        } else {
          productImage.value = img_original_src;
        }
      } else {
        productImage.value = img_original_src;
      }
    }
    function syncPrice(selected_options) {
      let hashed_key = getSelectionHash(selected_options);
      let product_price_el = document.querySelector("#price");
      let product_main_price = Number(product_price_el.getAttribute("data-main-price")).toFixed(0);
      if (product.value.additional_info_store[hashed_key]) {
        let attribute_price = product.value.additional_info_store[hashed_key]["additional_price"];
        if (attribute_price) {
          productPrice.value = Number(product_main_price) + Number(attribute_price);
        } else {
          productPrice.value = product_main_price;
        }
      } else {
        productPrice.value = product_main_price;
      }
    }
    function syncStock(selected_options) {
      let hashed_key = getSelectionHash(selected_options);
      if (product.value.additional_info_store[hashed_key]) {
        let stock_count = product.value.additional_info_store[hashed_key]["stock_count"];
        if (Number(stock_count) > 0) {
          variantStock.value = stock_count;
          outOfStock.value = 2;
        } else {
          outOfStock.value = 1;
        }
      }
    }
    function view_selected_options(selected_options) {
      let ordered_data = {};
      let selected_options_keys = Object.keys(selected_options).sort();
      selected_options_keys.map(function(e) {
        ordered_data[e] = String(selected_options[e]);
      });
      return ordered_data;
    }
    function getSelectionHash(selected_options) {
      return MD5(JSON.stringify(selected_options));
    }
    return {
      closeModal,
      variantProductAddToCart,
      product,
      childCategoryString,
      handleSizeListClick,
      productPrice,
      productImage,
      variantStock,
      outOfStock,
      productQuantity
    };
  }
};
function _sfc_ssrRender$4(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k;
  _push(`<!--[--><div class="popup-fixed interview-popup" data-v-0a9a5810>`);
  if (_ctx.product) {
    _push(`<div class="popup-contents" data-v-0a9a5810><span class="popup-contents-close popup-close" data-v-0a9a5810><i class="las la-times" data-v-0a9a5810></i></span><div class="modal-body" data-v-0a9a5810><div class="editProduct" data-v-0a9a5810><div class="row g-4" data-v-0a9a5810><div class="col-lg-12" data-v-0a9a5810><div class="dashboard_posSystem__category__nav__inner" data-v-0a9a5810><p class="dashboard_posSystem__category__nav__item" data-v-0a9a5810>${ssrInterpolate(_ctx.product.product.category.name)}</p><p class="dashboard_posSystem__category__nav__item" data-v-0a9a5810>${ssrInterpolate(_ctx.product.product.sub_category.name)}</p><p class="dashboard_posSystem__category__nav__item" data-v-0a9a5810>${ssrInterpolate(_ctx.childCategoryString(_ctx.product.product.child_category))}</p></div></div><div class="col-lg-4" data-v-0a9a5810><div class="editProduct__thumb" data-v-0a9a5810><div class="editProduct__thumb__main" data-v-0a9a5810><img${ssrRenderAttr("src", _ctx.productImage)} alt="" id="product-image-field"${ssrRenderAttr("data-src", _ctx.product.product.image)} data-v-0a9a5810></div></div><div class="shop-details-thumb-wrapper text-center d-flex gap-2 align-content-center justify-content-center" data-v-0a9a5810><!--[-->`);
    ssrRenderList(_ctx.product.product.gallery_images, (productGallery) => {
      _push(`<div class="shop-details-thums bg-item-five" data-v-0a9a5810><img${ssrRenderAttr("src", productGallery.image)} alt="" data-v-0a9a5810></div>`);
    });
    _push(`<!--]--></div></div><div class="col-lg-8" data-v-0a9a5810><div class="editProduct__contents" data-v-0a9a5810><h5 class="editProduct__contents__title" data-v-0a9a5810><a href="{{ product.product_url }}" data-v-0a9a5810>${ssrInterpolate(_ctx.product.product.name)}</a></h5><p data-v-0a9a5810> Brand: ${ssrInterpolate(_ctx.product.product.brand.name)}</p><p class="editProduct__contents__price mt-2" data-v-0a9a5810><b id="price"${ssrRenderAttr("data-main-price", _ctx.product.product.sale_price)} data-v-0a9a5810>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(_ctx.productPrice))}</b><s data-v-0a9a5810>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(_ctx.product.product.price))}</s></p>`);
    if (((_b = (_a = _ctx.product.product) == null ? void 0 : _a.inventory) == null ? void 0 : _b.stock_count) > 0) {
      _push(`<div class="text-success mt-2" data-v-0a9a5810> In Stock (${ssrInterpolate((_d = (_c = _ctx.product.product) == null ? void 0 : _c.inventory) == null ? void 0 : _d.stock_count)}) </div>`);
    } else {
      _push(`<!---->`);
    }
    if (((_f = (_e = _ctx.product.product) == null ? void 0 : _e.inventory) == null ? void 0 : _f.stock_count) < 1) {
      _push(`<div class="text-danger mt-2" data-v-0a9a5810> Out of stock </div>`);
    } else {
      _push(`<!---->`);
    }
    if ((((_i = (_h = (_g = _ctx.product) == null ? void 0 : _g.product) == null ? void 0 : _h.size) == null ? void 0 : _i.length) ?? 0) > 0) {
      _push(`<div class="valueInput value-input-area mt-4" data-v-0a9a5810><span class="valueInput__head" data-v-0a9a5810><strong data-v-0a9a5810> Size: </strong><input class="form--input value-color" name="color" type="text" value="" data-v-0a9a5810><input type="hidden" id="selected_size" data-v-0a9a5810></span><ul class="valueInput__list size-lists" data-type="Size" data-v-0a9a5810><!--[-->`);
      ssrRenderList(_ctx.product.product.size, (size) => {
        _push(`<li class="text-center"${ssrRenderAttr("data-value", size.id)}${ssrRenderAttr("data-display-value", size.name)} data-v-0a9a5810>${ssrInterpolate(size.name)}</li>`);
      });
      _push(`<!--]--></ul></div>`);
    } else {
      _push(`<!---->`);
    }
    if ((((_k = (_j = _ctx.product) == null ? void 0 : _j.productColors) == null ? void 0 : _k.length) ?? 0) > 0) {
      _push(`<div class="valueInput value-input-area mt-4" data-v-0a9a5810><span class="valueInput__head" data-v-0a9a5810><strong data-v-0a9a5810> Color: </strong><input class="form--input value-color" name="color" type="text" value="" data-v-0a9a5810><input type="hidden" id="selected_color" data-v-0a9a5810></span><ul class="valueInput__list size-lists" data-type="Color" data-v-0a9a5810><!--[-->`);
      ssrRenderList(_ctx.product.productColors, (color) => {
        _push(`<li class="text-center" style="${ssrRenderStyle({ background: color.color_code })}"${ssrRenderAttr("data-value", color.id)}${ssrRenderAttr("data-display-value", color.name)} data-v-0a9a5810></li>`);
      });
      _push(`<!--]--></ul></div>`);
    } else {
      _push(`<!---->`);
    }
    _push(`<!--[-->`);
    ssrRenderList(_ctx.product.available_attributes, (attribute, attr_name) => {
      _push(`<div class="value-input-area margin-top-15 attribute_options_list" data-v-0a9a5810><span class="input-list" data-v-0a9a5810><strong class="color-light" data-v-0a9a5810>${ssrInterpolate(attr_name)}</strong><input class="form--input value-size" type="text" value="" data-v-0a9a5810><input type="hidden" id="selected_attribute_option" name="selected_attribute_option" data-v-0a9a5810></span><ul class="size-lists"${ssrRenderAttr("data-type", attr_name)} data-v-0a9a5810><!--[-->`);
      ssrRenderList(attribute, (option) => {
        _push(`<li class=""${ssrRenderAttr("data-value", option)}${ssrRenderAttr("data-display-value", option)} data-v-0a9a5810>${ssrInterpolate(option)}</li>`);
      });
      _push(`<!--]--></ul></div>`);
    });
    _push(`<!--]--><div class="btn-wrapper btn_flex justify-content-start mt-4" data-v-0a9a5810><div class="product__quantity m-0 radius-5" data-v-0a9a5810><span class="substract" data-v-0a9a5810><i class="las la-minus" data-v-0a9a5810></i></span><input class="product__quantity__input radius-5" type="number"${ssrRenderAttr("value", _ctx.productQuantity)} data-v-0a9a5810><span class="plus" data-v-0a9a5810><i class="las la-plus" data-v-0a9a5810></i></span></div><a href="#1" class="posBtn btn_bg_1 radius-5" data-v-0a9a5810>Add to cart</a>`);
    if (_ctx.variantStock > 0) {
      _push(`<div class="text-success mt-2" data-v-0a9a5810> In Stock (${ssrInterpolate(_ctx.variantStock)}) </div>`);
    } else {
      _push(`<!---->`);
    }
    if (_ctx.outOfStock == 1) {
      _push(`<div class="text-danger mt-2" data-v-0a9a5810> Out of stock </div>`);
    } else {
      _push(`<!---->`);
    }
    _push(`</div></div></div></div></div></div></div>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</div><div class="popup-overlay" data-v-0a9a5810></div><!--]-->`);
}
const _sfc_setup$a = _sfc_main$a.setup;
_sfc_main$a.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/ProductModal.vue");
  return _sfc_setup$a ? _sfc_setup$a(props, ctx) : void 0;
};
const ProductModal = /* @__PURE__ */ _export_sfc(_sfc_main$a, [["ssrRender", _sfc_ssrRender$4], ["__scopeId", "data-v-0a9a5810"]]);
const SubmitButtonLoader_vue_vue_type_style_index_0_scoped_edba5256_lang = "";
const _sfc_main$9 = {};
function _sfc_ssrRender$3(_ctx, _push, _parent, _attrs) {
  _push(`<span${ssrRenderAttrs(mergeProps({ class: "loading-icon mdi mdi-loading" }, _attrs))} data-v-edba5256></span>`);
}
const _sfc_setup$9 = _sfc_main$9.setup;
_sfc_main$9.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/button/SubmitButtonLoader.vue");
  return _sfc_setup$9 ? _sfc_setup$9(props, ctx) : void 0;
};
const SubmitButtonLoader = /* @__PURE__ */ _export_sfc(_sfc_main$9, [["ssrRender", _sfc_ssrRender$3], ["__scopeId", "data-v-edba5256"]]);
const _sfc_main$8 = {
  __name: "AddCustomer",
  __ssrInlineRender: true,
  setup(__props) {
    const status = ref(false);
    const countries = ref({});
    const states = ref({});
    const cities = ref({});
    const isAvailableUsername = ref({});
    axios.get(window.appUrl + "/api/v1/get-countries").then((response) => {
      countries.value = response.data.countries;
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: "modal fade",
        id: "addCustomer"
      }, _attrs))}><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add a Customer</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><div class="custom-form"><form method="post"><div class="row g-4"><div class="col-sm-6"><div class="single-input"><label for="firstName" class="label_title">Full Name</label><input type="text" name="name" class="form--control radius-5" placeholder="Enter First Name" id="firstName"></div></div><div class="col-sm-6"><div class="single-input"><label for="PhoneNumber" class="label_title">Phone Number</label><input name="phone" type="tel" class="form--control radius-5" placeholder="Enter Phone Number" id="PhoneNumber"></div></div><div class="col-sm-12"><div class="single-input"><label for="emailAddress" class="label_title">Email Address</label><input name="email" type="email" class="form--control radius-5" placeholder="Enter Email Address" id="emailAddress"></div></div><div class="col-sm-12"><div class="single-input"><label for="emailAddress" class="label_title">Username</label><input name="username" type="text" class="form--control radius-5" placeholder="Enter username." id="username">`);
      if (isAvailableUsername.value) {
        _push(`<p class="${ssrRenderClass("info text-" + isAvailableUsername.value.type)}">${ssrInterpolate(isAvailableUsername.value.msg)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><div class="col-sm-12"><div class="single-input"><label for="emailAddress" class="label_title">Country</label><select name="country" class="form-control"><option value="">Select a county</option><!--[-->`);
      ssrRenderList(countries.value, (country) => {
        _push(`<option${ssrRenderAttr("value", country.id)}>${ssrInterpolate(country.name)}</option>`);
      });
      _push(`<!--]--></select></div></div><div class="col-sm-6"><div class="single-input"><label for="firstName" class="label_title">State</label><select name="state" class="form-control"><option value="">Select a state</option><!--[-->`);
      ssrRenderList(states.value, (state) => {
        _push(`<option${ssrRenderAttr("value", state.id)}>${ssrInterpolate(state.name)}</option>`);
      });
      _push(`<!--]--></select></div></div><div class="col-sm-6"><div class="single-input"><label for="lastName" class="label_title">City</label><select name="city" class="form-control"><option value="">Select a city</option><!--[-->`);
      ssrRenderList(cities.value, (city) => {
        _push(`<option${ssrRenderAttr("value", city.id)}>${ssrInterpolate(city.name)}</option>`);
      });
      _push(`<!--]--></select></div></div><div class="col-sm-12"><div class="single-input"><label for="customerAddress" class="label_title">Customer Address</label><input name="address" type="tel" class="form--control radius-5" placeholder="Enter Customer Address" id="customerAddress"></div></div></div><hr class="mb-0"><div class="modal-footer border-0"><button id="add-customer-close-modal-button" type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel </button><button id="add-customer-submit-modal-button" type="submit" class="btn btn-primary"> Add Customer `);
      _push(ssrRenderComponent(SubmitButtonLoader, {
        style: status.value ? null : { display: "none" }
      }, null, _parent));
      _push(`</button></div></form></div></div></div></div></div>`);
    };
  }
};
const _sfc_setup$8 = _sfc_main$8.setup;
_sfc_main$8.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/modal/AddCustomer.vue");
  return _sfc_setup$8 ? _sfc_setup$8(props, ctx) : void 0;
};
const _sfc_main$7 = {
  name: "SelectedCustomer",
  props: {
    customer: null
  }
};
function _sfc_ssrRender$2(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  if ($props.customer != null) {
    _push(`<div${ssrRenderAttrs(mergeProps({ class: "card mt-3" }, _attrs))}><div class="card-body mt-0"><div class="d-flex justify-content-between align-items-center py-0"><h6 class="m-0">Selected Customer</h6></div><hr><div class="searchWrap__item__flex"><div class="searchWrap__left"><div class="searchWrap__left__flex"><div class="searchWrap__left__thumb"><img${ssrRenderAttr("src", $props.customer.image)} alt="profile"></div><div class="searchWrap__left__contents"><h5 class="searchWrap__left__contents__title">${ssrInterpolate($props.customer.phone)}</h5><p class="searchWrap__left__contents__subtitle">${ssrInterpolate($props.customer.name)}</p></div></div></div><div class="searchWrap__right"><h6 class="text-center">User from</h6><p>${ssrInterpolate($props.customer.date)}</p></div></div></div></div>`);
  } else {
    _push(`<!---->`);
  }
}
const _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/customer/SelectedCustomer.vue");
  return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
const SelectedCustomer = /* @__PURE__ */ _export_sfc(_sfc_main$7, [["ssrRender", _sfc_ssrRender$2]]);
const _sfc_main$6 = {
  __name: "InvoiceComponent",
  __ssrInlineRender: true,
  props: ["invoice", "directPrint"],
  emits: ["printed"],
  setup(__props, { emit: emits }) {
    const props = __props;
    let siteInfo = props.invoice.info.site_info;
    let cartInfo = props.invoice.cart;
    let transactionInfo = props.invoice.transaction;
    let pricing = props.invoice.pricing;
    const invoiceContent = ref(null);
    const printInvoice = () => {
      if (invoiceContent.value) {
        const printContent = invoiceContent.value.innerHTML;
        const windowPrint = window.open("", "", "left=100,top=100,width=300,height=700,toolbar=0,scrollbars=1,status=0");
        windowPrint.document.write(`<link rel="stylesheet" href="${window.appUrl}/assets/backend/css/pos-invoice.css"/>`);
        windowPrint.document.write(printContent);
        windowPrint.document.close();
        setTimeout(() => {
          windowPrint.focus();
          if (props.directPrint) {
            windowPrint.print();
          }
        }, 250);
        emits("dataPrinted");
      }
    };
    onMounted(() => {
      printInvoice();
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: "receipt-container invoice",
        ref_key: "invoiceContent",
        ref: invoiceContent
      }, _attrs))}><header class="receipt-header"><h1>Money Receipt</h1><p><strong>${ssrInterpolate(unref(siteInfo).name)}</strong></p><p><strong>Email:</strong> ${ssrInterpolate(unref(siteInfo).email)}</p><p><strong>Website:</strong> ${ssrInterpolate(unref(siteInfo).website)}</p><p><strong>Date:</strong> ${ssrInterpolate(props.invoice.info.date)}</p></header><div class="receipt-body"><table class="receipt-table"><thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody><!--[-->`);
      ssrRenderList(unref(cartInfo), (item) => {
        _push(`<tr><td>${ssrInterpolate(item.name)}</td><td>${ssrInterpolate(item.qty)}</td><td>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(item.price))}</td><td>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(item.subtotal))}</td></tr>`);
      });
      _push(`<!--]--></tbody></table><div class="receipt-summary"><p><strong>Subtotal:</strong> ${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(unref(pricing).sub_total))}</p><p><strong>Tax (${ssrInterpolate(unref(pricing).tax)}%):</strong> ${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(unref(pricing).taxed_amount.toFixed(2)))}</p><p><strong>Total:</strong> ${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(unref(pricing).total))}</p><p><strong>Paid Amount:</strong> ${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(unref(transactionInfo).customerPaidAmount))}</p><p><strong>Change Due:</strong> ${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(unref(transactionInfo).changeAmount.toFixed(2)))}</p></div></div><footer class="receipt-footer"><p>Thank you for shopping with us!</p><p>Visit our website: ${ssrInterpolate(unref(siteInfo).website)}</p></footer></div>`);
    };
  }
};
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/InvoiceComponent.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const ProcedToCart_vue_vue_type_style_index_0_scoped_030a1f5e_lang = "";
const _sfc_main$5 = {
  name: "ProceedToCart",
  components: { InvoiceComponent: _sfc_main$6, SubmitButtonLoader, SelectedCustomer },
  props: {
    totalAmount: 0,
    customer: null
  },
  setup(props, { emit }) {
    const activePaymentTab = ref("cash");
    const invoiceData = reactive({});
    const buttonLoader = ref(false);
    const customerPaidAmount = ref(0);
    const disableSubmit = ref(false);
    const totalAmount = ref();
    const changeAmount = ref(0);
    totalAmount.value = props.totalAmount;
    const credentials = ref({
      pos_payment_gateway_enable: false,
      pos_card_payment_gateway_enable: false,
      pos_e_wallet_payment_gateway_enable: false
    });
    watch(() => props.totalAmount, (newValue, oldValue) => {
      totalAmount.value = newValue;
    });
    axios.get(window.appUrl + "/admin-home/pos/gateway-settings").then((response) => {
      credentials.value = response.data;
    }).catch((errors) => {
    });
    function handleGateway(val) {
      var _a, _b;
      let paymentGateway = document.querySelectorAll(".single_click");
      paymentGateway.forEach(function(element, key) {
        element.classList.remove("active");
      });
      (_b = (_a = document.querySelector(".single_click[data-name=" + val + "]")) == null ? void 0 : _a.classList) == null ? void 0 : _b.add("active");
      console.log(val);
      document.querySelector("#selected_gateway").value = val;
    }
    function handleTabs(selector) {
      document.querySelector("#cash").classList.remove("active");
      document.querySelector("#cards").classList.remove("active");
      document.querySelector("#" + selector).classList.add("active");
      activePaymentTab.value = selector;
      customerPaidAmount.value = 0;
      changeAmount.value = 0;
      document.querySelector(".customer-paid").value = 0;
    }
    function sendCustomerEmail(event) {
      if (event.currentTarget.checked) {
        document.querySelector("#form_send_email").value = "on";
      } else {
        document.querySelector("#form_send_email").value = "off";
      }
    }
    function handleCustomerPaid(event) {
      changeAmount.value = event.target.value - totalAmount.value;
      customerPaidAmount.value = event.target.value;
    }
    function handleSubmit(event) {
      event.preventDefault();
      if (activePaymentTab.value === "cash" && customerPaidAmount.value < 1) {
        toastr.error("You must enter a amount");
        return;
      }
      if (Math.round(totalAmount.value) > 0 == false) {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "Please add some product in to carts for purchase",
          showConfirmButton: false,
          timer: 1500
        });
        return;
      }
      if (activePaymentTab.value === "cards") {
        customerPaidAmount.value = Math.round(totalAmount.value);
        changeAmount.value = 0;
      }
      disableSubmit.value = true;
      buttonLoader.value = true;
      axios.post(window.appUrl + "/admin-home/pos/order/submit", new FormData(event.target)).then((response) => {
        if (response.data.type === "success") {
          emit("cartAdded");
          invoiceData.info = response.data.order_details;
          invoiceData.transaction = {
            customerPaidAmount: customerPaidAmount.value,
            changeAmount: changeAmount.value
          };
          emit("invoiceData", invoiceData);
          document.querySelector(".paymentMethod__cash__input input").value = 0;
          changeAmount.value = 0;
          customerPaidAmount.value = 0;
          activePaymentTab.value = "cash";
        }
        document.querySelector("#close-proceed-to-cart").dispatchEvent(new MouseEvent("click"));
        disableSubmit.value = false;
        buttonLoader.value = false;
        Swal.fire({
          position: "top-end",
          icon: response.data.type,
          title: response.data.msg,
          showConfirmButton: false,
          timer: response.data.timer ?? 1e3
        });
      });
    }
    const activeInactiveTab = (type) => {
      return activePaymentTab.value === type ? "active" : "";
    };
    return {
      credentials,
      handleTabs,
      handleGateway,
      handleCustomerPaid,
      handleSubmit,
      changeAmount,
      sendCustomerEmail,
      disableSubmit,
      customerPaidAmount,
      buttonLoader,
      invoiceData,
      activePaymentTab,
      activeInactiveTab
    };
  }
};
function _sfc_ssrRender$1(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  const _component_SelectedCustomer = resolveComponent("SelectedCustomer");
  const _component_SubmitButtonLoader = resolveComponent("SubmitButtonLoader");
  _push(`<div${ssrRenderAttrs(mergeProps({
    class: "modal fade",
    id: "proceedCart"
  }, _attrs))} data-v-030a1f5e><div class="modal-dialog" data-v-030a1f5e><div class="modal-content" data-v-030a1f5e><div class="modal-header" data-v-030a1f5e><h5 class="modal-title" data-v-030a1f5e>Payment Methods</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" data-v-030a1f5e></button></div><div class="modal-body" data-v-030a1f5e><div class="paymentMethod" data-v-030a1f5e><ul class="paymentMethod__card tabs" data-v-030a1f5e>`);
  if ($setup.credentials.pos_payment_gateway_enable == 1) {
    _push(`<li class="${ssrRenderClass([$setup.activeInactiveTab("cash"), "paymentMethod__card__item cash"])}" data-v-030a1f5e><p class="paymentMethod__card__name" data-tab="cash" data-v-030a1f5e><span class="icon" data-v-030a1f5e><i class="las la-money-bill" data-v-030a1f5e></i></span> Cash</p></li>`);
  } else {
    _push(`<!---->`);
  }
  if ($setup.credentials.pos_card_payment_gateway_enable == 1) {
    _push(`<li class="${ssrRenderClass([$setup.activeInactiveTab("cards"), "paymentMethod__card__item card"])}" data-v-030a1f5e><p class="paymentMethod__card__name" data-tab="cards" data-v-030a1f5e><span class="icon" data-v-030a1f5e><i class="las la-credit-card" data-v-030a1f5e></i></span> Cards</p></li>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</ul><div class="${ssrRenderClass([$setup.activeInactiveTab("cash"), "tab_content_item"])}" id="cash" data-v-030a1f5e><div class="paymentMethod__wrap" data-v-030a1f5e>`);
  _push(ssrRenderComponent(_component_SelectedCustomer, { customer: $props.customer }, null, _parent));
  _push(`<div class="paymentMethod__price mt-4" data-v-030a1f5e><p class="paymentMethod__price__para" data-v-030a1f5e>Total</p><p class="paymentMethod__price__title" data-v-030a1f5e>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount($props.totalAmount))}</p></div><div class="paymentMethod__cash mt-4" data-v-030a1f5e><div class="paymentMethod__cash__paid" data-v-030a1f5e><p class="paymentMethod__cash__para" data-v-030a1f5e>Enter amount customer paid</p><div class="paymentMethod__cash__input" data-v-030a1f5e><input type="text" class="customer-paid form--control" value="0" data-v-030a1f5e><span class="paymentMethod__cash__input__sign" data-v-030a1f5e><i class="material-symbols-outlined" data-v-030a1f5e></i></span></div></div><div class="paymentMethod__cash__return mt-4" data-v-030a1f5e><p class="paymentMethod__cash__return__para" data-v-030a1f5e>Change amount</p><h4 class="paymentMethod__cash__return__price" data-v-030a1f5e>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount($setup.changeAmount.toFixed(2)))}</h4></div></div></div></div><div class="${ssrRenderClass([$setup.activeInactiveTab("cards"), "tab_content_item"])}" id="cards" data-v-030a1f5e><div class="paymentMethod__wrap" data-v-030a1f5e>`);
  _push(ssrRenderComponent(_component_SelectedCustomer, { customer: $props.customer }, null, _parent));
  _push(`<div class="paymentMethod__price mt-4" data-v-030a1f5e><p class="paymentMethod__price__para" data-v-030a1f5e>Total</p><p class="paymentMethod__price__title" data-v-030a1f5e>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount($props.totalAmount))}</p></div></div></div><div class="form-group d-flex gap-3 align-items-center" data-v-030a1f5e><label for="send_customer_email" data-v-030a1f5e>Send customer email</label><input type="checkbox" id="send_customer_email" class="form-check" data-v-030a1f5e><p class="info" data-v-030a1f5e>If you want to send an e-mail by selecting this then you should select an customer first. </p></div></div></div><div class="modal-footer" data-v-030a1f5e><form data-v-030a1f5e><input type="hidden" name="selected_gateway"${ssrRenderAttr("value", $setup.activePaymentTab)} id="selected_gateway" data-v-030a1f5e><input type="hidden" name="selected_customer" value="" id="selected_customer" data-v-030a1f5e><input type="hidden" name="coupon" value="" id="form_coupon" data-v-030a1f5e><input type="hidden" name="send_email" value="" id="form_send_email" data-v-030a1f5e><button id="close-proceed-to-cart" type="button" class="btn btn-danger mx-2" data-bs-dismiss="modal" data-v-030a1f5e>Cancel</button><button id="submit-proceed-to-cart-btn mx-2" type="submit" class="btn btn-primary"${ssrIncludeBooleanAttr($setup.disableSubmit) ? " disabled" : ""} data-v-030a1f5e>Pay Now `);
  _push(ssrRenderComponent(_component_SubmitButtonLoader, {
    style: $setup.buttonLoader ? null : { display: "none" }
  }, null, _parent));
  _push(`</button></form></div></div></div></div>`);
}
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/modal/ProcedToCart.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const ProceedToCart = /* @__PURE__ */ _export_sfc(_sfc_main$5, [["ssrRender", _sfc_ssrRender$1], ["__scopeId", "data-v-030a1f5e"]]);
const _sfc_main$4 = {
  __name: "StoreLocation",
  __ssrInlineRender: true,
  setup(__props) {
    const location = ref(false);
    const locationSettingsUrl = ref("#");
    const hasLocation = () => {
      axios.get(window.appUrl + "/admin-home/pos/store-location").then((response) => {
        location.value = response.data.location == null;
      }).catch((error) => {
        prepare_errors(error);
      });
    };
    const locationSettingsPage = () => {
      axios.get(window.appUrl + "/admin-home/pos/location-settings").then((response) => {
        locationSettingsUrl.value = response.data;
      }).catch((error) => {
        prepare_errors(error);
      });
    };
    onMounted(() => {
      hasLocation();
      locationSettingsPage();
    });
    return (_ctx, _push, _parent, _attrs) => {
      if (location.value) {
        _push(`<p${ssrRenderAttrs(mergeProps({ class: "alert alert-danger" }, _attrs))}>Please select your store location first. <a class="text-primary"${ssrRenderAttr("href", locationSettingsUrl.value)}>Read more</a></p>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/StoreLocation.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const ProceedToHold_vue_vue_type_style_index_0_scoped_f2c0de98_lang = "";
const _sfc_main$3 = {
  __name: "ProceedToHold",
  __ssrInlineRender: true,
  props: ["cartProducts", "cartTotalAmount"],
  emits: ["newCartProducts"],
  setup(__props, { emit }) {
    const props = __props;
    const buttonLoader = ref(false);
    ref(null);
    const getDateTime = () => {
      const now = /* @__PURE__ */ new Date();
      const date = now.getDate();
      const month = now.getMonth() + 1;
      const year = now.getFullYear();
      let hour = now.getHours();
      const minute = now.getMinutes();
      const second = now.getSeconds();
      const amPm = now.getHours() >= 12 ? "PM" : "AM";
      hour = hour % 12;
      hour = hour ? hour : 12;
      return `Cart-${date}-${month}-${year},${hour}:${minute}:${second}${amPm}`;
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: "modal fade",
        id: "proceedHold"
      }, _attrs))} data-v-f2c0de98><div class="modal-dialog" data-v-f2c0de98><div class="modal-content bg-white" data-v-f2c0de98><div class="modal-header" data-v-f2c0de98><h5 class="modal-title" data-v-f2c0de98>Hold This Order For Later</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" data-v-f2c0de98></button></div><div class="modal-body mt-3 mb-3" data-v-f2c0de98><!--[-->`);
      ssrRenderList(props.cartProducts, (product) => {
        _push(`<div class="dashboard_posSystem__sidebar__cart__item" data-v-f2c0de98><div class="dashboard_posSystem__sidebar__cart__item__flex" data-v-f2c0de98><p class="dashboard_posSystem__sidebar__cart__item__para" data-v-f2c0de98>${ssrInterpolate(product.name)} x ${ssrInterpolate(product.qty)}</p><p class="dashboard_posSystem__sidebar__cart__item__price" data-v-f2c0de98>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(product.price))}</p></div></div>`);
      });
      _push(`<!--]--><div class="dashboard_posSystem__sidebar__cart__total" data-v-f2c0de98><div class="dashboard_posSystem__sidebar__cart__item__flex" data-v-f2c0de98><p class="dashboard_posSystem__sidebar__cart__item__para" data-v-f2c0de98>Subtotal:</p><p class="dashboard_posSystem__sidebar__cart__item__price" data-v-f2c0de98>${ssrInterpolate(_ctx.getCurrencySymbolWithAmount(props.cartTotalAmount.sub_total))}</p></div></div><div class="mt-3" data-v-f2c0de98><label for="cartSaveName" data-v-f2c0de98>Cart Name To Be Saved</label><input id="cartSaveName" class="form--control" type="text"${ssrRenderAttr("value", getDateTime())} data-v-f2c0de98></div></div><div class="modal-footer" data-v-f2c0de98><form data-v-f2c0de98><button id="close-proceed-to-hold" type="button" class="btn btn-danger mx-2" data-bs-dismiss="modal" data-v-f2c0de98>Cancel</button><button id="submit-proceed-to-hold-btn" type="button" class="btn btn-primary mx-2" data-v-f2c0de98>Hold `);
      _push(ssrRenderComponent(SubmitButtonLoader, {
        style: buttonLoader.value ? null : { display: "none" }
      }, null, _parent));
      _push(`</button></form></div></div></div></div>`);
    };
  }
};
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/modal/ProceedToHold.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const ProceedToHold = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["__scopeId", "data-v-f2c0de98"]]);
const HoldOrders_vue_vue_type_style_index_0_scoped_17f6a33f_lang = "";
const _sfc_main$2 = {
  __name: "HoldOrders",
  __ssrInlineRender: true,
  props: ["cartTotalAmount"],
  emits: null,
  setup(__props, { emit: emits }) {
    const cartTotalAmount = __props;
    const holdOrders = ref([]);
    const showOrderList = ref(false);
    const holdOrderButton = ref(false);
    const getOnlyCartItems = () => {
      axios.get(window.appUrl + "/admin-home/pos/get-hold-order").then((response) => {
        holdOrders.value = response.data;
        holdOrderButton.value = response.data.length > 0;
      }).catch((errors) => {
        prepare_errors(errors);
      });
    };
    watch(() => cartTotalAmount.cartTotalAmount, (newValue, oldValue) => {
      if (newValue === 0) {
        getOnlyCartItems();
      }
    });
    onMounted(() => {
      getOnlyCartItems();
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[--><div id="addCustomerButton" style="${ssrRenderStyle(holdOrderButton.value ? null : { display: "none" })}" class="${ssrRenderClass(["dashboard_posSystem__sidebar__item radius-10 padding-20", { "bg-white": !showOrderList.value, "bg-primary text-white": showOrderList.value }])}" data-v-17f6a33f><a href="#0" class="addCustomer" data-v-17f6a33f><i class="las la-list" data-v-17f6a33f></i> Hold Order List <i class="${ssrRenderClass(["hold-icon las", { "la-angle-down": showOrderList.value, "la-angle-left": !showOrderList.value }])}" data-v-17f6a33f></i></a></div>`);
      if (showOrderList.value && holdOrderButton.value) {
        _push(`<div class="dashboard_posSystem__sidebar__item bg-white radius-10 padding-20" data-v-17f6a33f><div class="card mt-3" data-v-17f6a33f><div class="card-body mt-0" data-v-17f6a33f><div class="d-flex justify-content-between align-items-center py-0" data-v-17f6a33f><h6 class="m-0" data-v-17f6a33f>Hold Orders</h6></div><hr data-v-17f6a33f><div class="searchWrap__item__flex" data-v-17f6a33f><div class="searchWrap__left mt-3" data-v-17f6a33f>`);
        if (holdOrders.value.length > 0) {
          _push(`<!--[-->`);
          ssrRenderList(holdOrders.value, (order) => {
            _push(`<div class="searchWrap__left__contents d-flex justify-content-between mb-3" data-v-17f6a33f><h5 class="searchWrap__left__contents__title" data-v-17f6a33f>${ssrInterpolate(holdOrders.value.indexOf(order) + 1)}. ${ssrInterpolate(order.name)}</h5><div class="d-flex gap-2" data-v-17f6a33f><a class="tick-btn btn-info" data-v-17f6a33f><i class="las la-check" data-v-17f6a33f></i></a><a class="tick-btn btn-danger" data-v-17f6a33f><i class="las la-times" data-v-17f6a33f></i></a></div></div>`);
          });
          _push(`<!--]-->`);
        } else {
          _push(`<div class="searchWrap__left__contents mb-3" data-v-17f6a33f><p class="text-center" data-v-17f6a33f>No order is saved yet</p></div>`);
        }
        _push(`</div></div></div></div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<!--]-->`);
    };
  }
};
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/sidebar/HoldOrders.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const HoldOrders = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["__scopeId", "data-v-17f6a33f"]]);
const InvoiceModal_vue_vue_type_style_index_0_scoped_d7cd7748_lang = "";
const _sfc_main$1 = {
  __name: "InvoiceModal",
  __ssrInlineRender: true,
  props: ["orderDetails"],
  emits: null,
  setup(__props, { emit: emits }) {
    ref(false);
    const loaderButton = ref(false);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: "modal fade",
        id: "invoiceModal",
        "data-bs-backdrop": "static",
        "data-bs-keyboard": "false"
      }, _attrs))} data-v-d7cd7748><div class="modal-dialog" data-v-d7cd7748><div class="modal-content bg-white" data-v-d7cd7748><div class="modal-header" data-v-d7cd7748><h5 class="modal-title" data-v-d7cd7748>Order Complete</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" data-v-d7cd7748></button></div><div class="modal-body my-3 mb-5 text-center" data-v-d7cd7748><p class="mb-3" data-v-d7cd7748><strong data-v-d7cd7748>Order successfully completed! You can view the order details or print the receipt using the options below.</strong></p><div class="button-wrappers d-flex justify-content-center gap-3 mt-5" data-v-d7cd7748><button id="submit-proceed-to-hold-btn" type="button" class="btn btn-primary" data-v-d7cd7748>Order Details</button><button id="submit-proceed-to-hold" type="button" class="btn btn-info" data-v-d7cd7748>Show Receipt `);
      if (loaderButton.value) {
        _push(ssrRenderComponent(SubmitButtonLoader, null, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</button><button id="submit-proceed-to-hold" type="button" class="btn btn-success" data-v-d7cd7748>Print Receipt `);
      if (loaderButton.value) {
        _push(ssrRenderComponent(SubmitButtonLoader, null, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</button></div></div><div class="modal-footer d-flex" data-v-d7cd7748><button id="close-proceed-to-hold" type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" data-v-d7cd7748>Cancel</button></div></div></div></div>`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/components/modal/InvoiceModal.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const InvoiceModal = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["__scopeId", "data-v-d7cd7748"]]);
const app_vue_vue_type_style_index_0_lang = "";
const _sfc_main = {
  name: "app",
  components: {
    InvoiceModal,
    InvoiceComponent: _sfc_main$6,
    HoldOrders,
    ProceedToHold,
    StoreLocation: _sfc_main$4,
    ProceedToCart,
    AddCustomer: _sfc_main$8,
    ProductModal,
    ProductCard,
    Calculate,
    LeftSidebar,
    Customer
    // CartItems
  },
  setup: (props, { emit }) => {
    const Products = reactive({ products: "", pagination: "" });
    const Hello = ref(0);
    const loadCartItems = ref(false);
    const productDetails = ref(false);
    const loadProductDetails = ref(false);
    const cartTotalAmount = ref(0);
    const calculateTotalAmount = ref(0);
    const customer = ref(null);
    const isSearchProduct = ref(false);
    const selectedCategories = ref({ category: { name: "" }, sub_category: { name: "" }, child_category: { name: "" } });
    const currentPage = ref(1);
    const cartProducts = ref({});
    const clearCartItems = ref(false);
    const invoiceData = reactive({});
    const directPrint = ref(false);
    function Increase() {
      Hello.value = Hello.value + 1;
    }
    function selectedCustomer(value) {
      customer.value = value;
    }
    function get_cart_amount(data) {
      cartTotalAmount.value = data;
    }
    function getCartProducts(data) {
      cartProducts.value = data;
    }
    function fetchProductData() {
      axios.get(window.appUrl + "/api/v1/product").then((response) => {
        Products.products = response.data.data;
        Products.pagination = response.data;
        currentPage.value = response.data.current_page;
        delete Products.pagination["data"];
      }).catch((error) => {
      });
    }
    function searchProduct(data) {
      isSearchProduct.value = true;
      Products.value = [];
      axios.get(window.appUrl + "/api/v1/product?" + data).then((response) => {
        isSearchProduct.value = false;
        Products.products = response.data.data;
        Products.pagination = response.data;
        currentPage.value = response.data.current_page;
        delete Products.pagination["data"];
      }).catch((error) => {
        isSearchProduct.value = false;
        prepare_errors(error);
      });
    }
    function addToCartButton(product) {
      Swal.fire({
        title: "Error!",
        text: "Do you want to continue",
        icon: "error",
        confirmButtonText: "Cool"
      });
    }
    function testCallback() {
      loadCartItems.value = !loadCartItems.value;
      loadProductDetails.value = !loadProductDetails.value;
    }
    function loadProductData(emitData) {
      productDetails.value = emitData;
    }
    function calculateTotal(value) {
      calculateTotalAmount.value = value;
    }
    document.querySelector(".body-overlay").addEventListener("click", function(event) {
      this.classList.remove("show");
      document.querySelector(".searchParent .searchWrap").classList.remove("d-block");
      document.querySelector(".searchParent .searchWrap").classList.add("d-none");
      document.querySelector(".keyupInput").value = "";
    });
    function handleCategoryOverlay() {
      document.querySelector(".dashboard_posSystem__category__nav").dispatchEvent(new MouseEvent("click"));
    }
    function handleCategorySelection(data) {
      selectedCategories.value = data.value;
    }
    function fetchProductsPagination(page) {
      if (page === "")
        return;
      axios.get(page).then((response) => {
        Products.products = [...Products.products, ...response.data.data];
        Products.pagination = response.data;
        delete Products.pagination["data"];
      }).catch((error) => {
      });
    }
    const loadMore = () => {
      var _a, _b;
      fetchProductsPagination(((_b = (_a = Products.pagination) == null ? void 0 : _a.links) == null ? void 0 : _b.next) ?? "");
    };
    const getAppUrl = () => {
      return window.appUrl;
    };
    const clearSelectedCategories = () => {
      selectedCategories.value = { category: { name: "" }, sub_category: { name: "" }, child_category: { name: "" } };
      fetchProductData();
    };
    const updateAfterHold = () => {
      cartTotalAmount.value = 0;
      clearCartItems.value = true;
    };
    const afterCartCleared = () => {
      clearCartItems.value = false;
    };
    const restoreCart = () => {
      loadCartItems.value = !loadCartItems.value;
    };
    const getInvoiceData = (data) => {
      invoiceData.info = data.info;
      invoiceData.transaction = data.transaction;
      invoiceData.cart = cartProducts.value;
      invoiceData.pricing = cartTotalAmount.value;
      let invoice_modal = new bootstrap.Modal(document.getElementById("invoiceModal"));
      invoice_modal.show();
    };
    const getPrintInvoiceStatus = (data) => {
      invoiceData.status = data;
    };
    const invoicePrinted = () => {
      invoiceData.status = false;
      directPrint.value = false;
    };
    const directPrintReceipt = () => {
      directPrint.value = true;
      invoiceData.status = true;
    };
    provide("fetchProductData", fetchProductData);
    provide("selectedCategories", selectedCategories);
    return {
      Hello,
      Increase,
      Products,
      fetchProductData,
      addToCartButton,
      testCallback,
      loadCartItems,
      loadProductData,
      productDetails,
      searchProduct,
      cartTotalAmount,
      get_cart_amount,
      calculateTotal,
      calculateTotalAmount,
      selectedCustomer,
      customer,
      handleCategoryOverlay,
      isSearchProduct,
      handleCategorySelection,
      selectedCategories,
      fetchProductsPagination,
      // pageNumbers,
      currentPage,
      getAppUrl,
      clearSelectedCategories,
      cartProducts,
      getCartProducts,
      updateAfterHold,
      clearCartItems,
      afterCartCleared,
      restoreCart,
      loadMore,
      getInvoiceData,
      invoiceData,
      invoicePrinted,
      getPrintInvoiceStatus,
      directPrintReceipt,
      directPrint
    };
  },
  mounted() {
    this.fetchProductData();
    document.querySelector("body").classList.add("sidebar-icon-only");
    document.querySelector("body .dashboard-top-contents.mb-4").remove();
  }
};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs, $props, $setup, $data, $options) {
  var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l, _m, _n, _o, _p, _q, _r, _s, _t, _u;
  const _component_StoreLocation = resolveComponent("StoreLocation");
  const _component_TopHeader = resolveComponent("TopHeader");
  const _component_ProductCard = resolveComponent("ProductCard");
  const _component_Customer = resolveComponent("Customer");
  const _component_HoldOrders = resolveComponent("HoldOrders");
  const _component_CartItems = resolveComponent("CartItems");
  const _component_Calculate = resolveComponent("Calculate");
  const _component_ProductModal = resolveComponent("ProductModal");
  const _component_AddCustomer = resolveComponent("AddCustomer");
  const _component_ProceedToCart = resolveComponent("ProceedToCart");
  const _component_ProceedToHold = resolveComponent("ProceedToHold");
  const _component_InvoiceModal = resolveComponent("InvoiceModal");
  const _component_InvoiceComponent = resolveComponent("InvoiceComponent");
  _push(`<!--[--><div class="body-overlay"></div><div class="dashboard__area"><div class="container-fluid p-0"><div class="dashboard__contents__wrapper iocn_view"><div class="dashboard__right"><div class="dashboard__body posPadding"><div class="dashboard__inner"><div class="dashboard_posSystem"><div class="dashboard__inner__item"><div class="row g-4"><div class="col-xxl-8 col-lg-7"><div class="dashboard_posSystem__left"><div class="dashboard_posSystem__header">`);
  _push(ssrRenderComponent(_component_StoreLocation, null, null, _parent));
  _push(ssrRenderComponent(_component_TopHeader, {
    onEmitCategorySelected: _ctx.handleCategorySelection,
    onEmitSearch: _ctx.searchProduct
  }, null, _parent));
  _push(`<div class="selected-categories d-flex gap-1">`);
  if (((_b = (_a = _ctx.selectedCategories) == null ? void 0 : _a.category) == null ? void 0 : _b.name) ?? false) {
    _push(`<div class="category">${ssrInterpolate(((_d = (_c = _ctx.selectedCategories) == null ? void 0 : _c.category) == null ? void 0 : _d.name) ?? "")} ${ssrInterpolate(((_f = (_e = _ctx.selectedCategories) == null ? void 0 : _e.sub_category) == null ? void 0 : _f.name) ? " > " : "")}</div>`);
  } else {
    _push(`<!---->`);
  }
  if (((_h = (_g = _ctx.selectedCategories) == null ? void 0 : _g.sub_category) == null ? void 0 : _h.name) ?? false) {
    _push(`<div class="sub_category">${ssrInterpolate(((_j = (_i = _ctx.selectedCategories) == null ? void 0 : _i.sub_category) == null ? void 0 : _j.name) ?? "")} ${ssrInterpolate(((_l = (_k = _ctx.selectedCategories) == null ? void 0 : _k.child_category) == null ? void 0 : _l.name) ? " > " : "")}</div>`);
  } else {
    _push(`<!---->`);
  }
  if (((_n = (_m = _ctx.selectedCategories) == null ? void 0 : _m.child_category) == null ? void 0 : _n.name) ?? false) {
    _push(`<div class="child_category">${ssrInterpolate(((_p = (_o = _ctx.selectedCategories) == null ? void 0 : _o.child_category) == null ? void 0 : _p.name) ?? "")}</div>`);
  } else {
    _push(`<!---->`);
  }
  if (((_q = _ctx.selectedCategories.category) == null ? void 0 : _q.name) !== "" || ((_r = _ctx.selectedCategories.sub_category) == null ? void 0 : _r.name) !== "" || ((_s = _ctx.selectedCategories.child_category) == null ? void 0 : _s.name) !== "") {
    _push(`<div><i class="cross-icon las la-times"></i></div>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</div></div><div class="dashboard_posSystem__row mt-4 scrollWrap px-3" style="${ssrRenderStyle({ "max-height": "1000px" })}">`);
  if (_ctx.Products.products.length > 0) {
    _push(`<!--[-->`);
    ssrRenderList(_ctx.Products.products, (product) => {
      _push(`<div class="dashboard_posSystem__item">`);
      _push(ssrRenderComponent(_component_ProductCard, {
        product,
        onCartAdded: _ctx.testCallback,
        onLoadProductView: _ctx.loadProductData,
        "load-cart-items": _ctx.loadCartItems
      }, null, _parent));
      _push(`</div>`);
    });
    _push(`<!--]-->`);
  } else {
    _push(`<!---->`);
  }
  _push(`<div class="${ssrRenderClass(_ctx.isSearchProduct ? "py-4 searching-product" : "py-4 no-product-found")}" style="${ssrRenderStyle(_ctx.isSearchProduct || _ctx.Products.products.length < 1 ? null : { display: "none" })}"><h3>${ssrInterpolate(_ctx.isSearchProduct ? "Searching product...." : "No product found")}</h3></div></div>`);
  if (((_u = (_t = _ctx.Products.pagination) == null ? void 0 : _t.links) == null ? void 0 : _u.next) ?? false) {
    _push(`<div class="load-more-products text-center mt-5 mb-3"><a class="btn btn-info">Load More</a></div>`);
  } else {
    _push(`<!---->`);
  }
  _push(`</div></div><div class="col-xxl-4 col-lg-5"><div class="dashboard_posSystem__sidebar">`);
  _push(ssrRenderComponent(_component_Customer, { onSelectedCustomer: _ctx.selectedCustomer }, null, _parent));
  _push(ssrRenderComponent(_component_HoldOrders, {
    "cart-total-amount": _ctx.cartTotalAmount,
    onCartItemRestored: _ctx.restoreCart
  }, null, _parent));
  _push(ssrRenderComponent(_component_CartItems, {
    onCartAmountWithTaxAmount: _ctx.get_cart_amount,
    onCartProducts: _ctx.getCartProducts,
    "load-cart-items": _ctx.loadCartItems,
    onCartAdded: _ctx.testCallback,
    "clear-cart": _ctx.clearCartItems,
    onCartCleared: _ctx.afterCartCleared
  }, null, _parent));
  _push(ssrRenderComponent(_component_Calculate, {
    onOrderCanceled: _ctx.testCallback,
    onTotalAmount: _ctx.calculateTotal,
    data: _ctx.cartTotalAmount
  }, null, _parent));
  _push(`</div></div></div></div></div></div></div></div></div></div></div>`);
  _push(ssrRenderComponent(_component_ProductModal, {
    "product-details": _ctx.productDetails,
    onCartAdded: _ctx.testCallback
  }, null, _parent));
  _push(ssrRenderComponent(_component_AddCustomer, null, null, _parent));
  _push(ssrRenderComponent(_component_ProceedToCart, {
    style: _ctx.cartTotalAmount.sub_total > 0 ? null : { display: "none" },
    onCartAdded: _ctx.testCallback,
    customer: _ctx.customer,
    "total-amount": _ctx.calculateTotalAmount,
    onInvoiceData: _ctx.getInvoiceData
  }, null, _parent));
  _push(ssrRenderComponent(_component_ProceedToHold, {
    onNewCartProducts: _ctx.updateAfterHold,
    "cart-products": _ctx.cartProducts,
    "cart-total-amount": _ctx.cartTotalAmount
  }, null, _parent));
  _push(ssrRenderComponent(_component_InvoiceModal, {
    "order-details": _ctx.invoiceData.info,
    onDirectPrint: _ctx.directPrintReceipt,
    onInvoiceDataStatus: _ctx.getPrintInvoiceStatus
  }, null, _parent));
  if (_ctx.invoiceData.status) {
    _push(ssrRenderComponent(_component_InvoiceComponent, {
      onDataPrinted: _ctx.invoicePrinted,
      invoice: _ctx.invoiceData,
      "direct-print": _ctx.directPrint
    }, null, _parent));
  } else {
    _push(`<!---->`);
  }
  _push(`<div class="category_overlay"></div><!--]-->`);
}
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/vue/layouts/app.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const AppLayout = /* @__PURE__ */ _export_sfc(_sfc_main, [["ssrRender", _sfc_ssrRender]]);
const app = createApp(AppLayout);
app.mixin({
  methods: {
    getCurrencySymbolWithAmount
  }
});
app.config.globalProperties.currencySymbol = window.currencySymbol;
app.component("TopHeader", TopHeader);
app.component("CartItems", CartItems);
app.mount("#app");
