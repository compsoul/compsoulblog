/**
 ***********************************************************************************************
 *                             COMPSOUL BLOG MODULE - LEGAL NOTICE                             *
 ***********************************************************************************************
 * The Compsoul Blog module ("the Module") is the intellectual property of Daniel Dziedzic,
 * trading under the name "Compsoul". By downloading, installing, or using the Module, you
 * ("the User") agree to be bound by the following terms and conditions:
 *
 * 1. Modification and Customization:
 * The User is permitted to modify and customize the Module for their own use, provided that
 * any substantial modifications or alterations are made in consultation with the original
 * creator, Daniel Dziedzic. The User must seek approval for any significant changes to the Module.
 *
 * 2. Non-Profit Use:
 * The Module can be used by non-profit organizations without any restrictions, subject to
 * compliance with all other terms and conditions specified herein.
 *
 * 3. Prohibited Resale:
 * The User is strictly prohibited from selling, leasing, licensing, or distributing the Module
 * in any form, either as it is or in modified versions, for commercial or monetary gain. The Module
 * is intended for non-commercial use only.
 *
 * 4. Consultation with the Creator:
 * Any attempts to modify, alter, or distribute the Module, in part or in whole, must be communicated
 * and approved by the original creator, Daniel Dziedzic. For any inquiries or consultation, please
 * contact the creator via email at daniel@compsoul.pl.
 *
 * 5. Creator Information:
 * The Module has been created by Daniel Dziedzic, trading under the name "Compsoul". For more
 * information about the creator and their work, please visit the official website at compsoul.pl
 * (for Polish) or compsoul.dev (for English).
 *
 * 6. Display of Creator Information:
 * The User agrees to include a visible and accessible link to the creator's website, compsoul.pl or
 * compsoul.dev, at the bottom of the list of posts on the frontend of their website using the Module.
 * The link should be displayed prominently and must be maintained at all times.
 *
 * Contact:
 * For technical support or to obtain permission for Module modifications, please contact the
 * creator:
 *
 * @author Daniel Dziedzic <daniel@compsoul.pl>
 * @copyright Compsoul
 * @license Free License with Attribution
 * @link https://compsoul.pl
 * @link https://compsoul.dev
 *
 * The User acknowledges and agrees that any use of the Module is subject to the terms and conditions
 * outlined in this legal notice. Failure to comply with these terms may result in legal action.
 ***********************************************************************************************
 *                                     END OF LEGAL NOTICE                                     *
 ***********************************************************************************************
 */

class CarouselCompsoulBlog {
  constructor(selector) {
    window.CompsoulBlog = window.CompsoulBlog || window.jQuery;
    this.selector = selector;
    this.carousel = new CompsoulBlog(this.selector);
    this.settings = {
      first: 0,
      active: false,
      parent: false,
      node: false,
      prev: "#carousel .carousel-prev",
      next: "#carousel .carousel-next",

      loop: false,
      type: "horizontal",

      activeClassName: "active",

      responsive: {}
    }

    this.carousel.each = this.carousel.each || this.carousel.each;
    this.carousel.ajax = this.carousel.ajax || CompsoulBlog.ajax;

  }

  root() {
    this.index = (this.settings.first < 0) ? 0 : (this.settings.first >= this.carousel.length ? this.carousel.length - 1 : this.settings.first);
    this.parent = (this.settings.parent) ? this.settings.parent : this.carousel[this.index].parentElement;
    this.prev = (this.settings.prev) ? this.settings.prev : false;
    this.next = (this.settings.next) ? this.settings.next : false;
    this.node = (this.settings.node) ? this.settings.node : false;
  }

  classes(settings) {
    class Library {
      constructor(object) {
        this.object = object;
        this.settings = settings;
      }

      active() {
        this.object.addClass(this.settings.activeClassName);
        return this;
      }

      inactive() {
        this.object.removeClass(this.settings.activeClassName);
        return this;
      }

      change(Library) {
        let len = this.object.length;
        while(len--) {
          this[len] = new Library(new CompsoulBlog(this.object[len]));
        }
        return this;
      }
    }

    this.items = new Library(this.carousel).change(Library);

    if(this.prev) this.prev = new Library(new CompsoulBlog(this.prev));
    if(this.next) this.next = new Library(new CompsoulBlog(this.next));
    if(this.node) this.node = new Library(new CompsoulBlog(this.node));
  }

  set(index) {
    if(!this.settings.loop && (index < 0) || index > this.carousel.length - 1) return false;
    if(!this.settings.loop) this.nonrotative(index);
  }

  nonrotative(index) {
    if(this.prev) (index <= 0) ? this.prev.inactive() : this.prev.active();
    if(this.next) ((index >= this.carousel.length - 1) || (!this.settings.active && (this.limit(index).bound || this.limit(index).bound === 0))) ? this.next.inactive() : this.next.active();

    (this.settings.type === "vertical") ? this.transformY((this.limit(index).bound) ? (this.limit(index).bound) : this.height(index)) : this.transformX((this.limit(index).bound) ? (this.limit(index).bound) : this.width(index));
    this.index = (!this.settings.active && this.limit(index).bound) ? this.length() : index;
    if(this.settings.active) this.items.inactive()[this.index].active();
  }

  limit(index) {
    let parentWidth = this.parent.offsetWidth,
        carouselWidth = this.width(this.carousel.length),
        translateX = this.width(index),
        parentHeight = this.parent.offsetHeight,
        carouselHeight = this.height(this.carousel.length),
        translateY = this.height(index);

    return {
      breakpoint: (this.settings.type === "vertical") ? (carouselHeight - parentHeight < translateY) || (index <= 0) : (carouselWidth - parentWidth < translateX) || (index <= 0),
      bound: (this.settings.type === "vertical") ? (carouselHeight - parentHeight <= translateY) ? carouselHeight - parentHeight : false : (carouselWidth - parentWidth <= translateX) ? carouselWidth - parentWidth : false
    }
  }

  length() {
    for (let index = 0; index < this.carousel.length; index++) {
      if(this.limit(index).bound) return index;
    }
  }

  height(index) {
    let height = 0;

    new CompsoulBlog(this.selector + ":not(:nth-child(n+" + (index + 1) + "))").each(function() {
      const style = window.getComputedStyle ? window.getComputedStyle(this) : this.currentStyle;

      height += this.getBoundingClientRect().height;
      height += parseFloat(style.marginTop) + parseFloat(style.marginBottom);
    });

    return height;
  }

  width(index) {
    let width = 0;

    new CompsoulBlog(this.selector + ":not(:nth-child(n+" + (index + 1) + "))").each(function() {
      const style = window.getComputedStyle ? window.getComputedStyle(this) : this.currentStyle;

      width += this.getBoundingClientRect().width;
      width += parseFloat(style.marginLeft) + parseFloat(style.marginRight);
    });

    return width;
  }

  transformX(value) {
    this.parent.style.transform = "translate3d(-" + value + "px, 0, 0)";
  }

  transformY(value) {
    this.parent.style.transform = "translate3d(0, -" + value + "px, 0)";
  }

  event() {
    if(this.prev) {
      this.prevClickEvent = () => {
        this.set(this.index - 1);
      };
      this.prev.object.on("click", this.prevClickEvent);
    }

    if(this.next) {
      this.nextClickEvent = () => {
        this.set(this.index + 1);
      };
      this.next.object.on("click", this.nextClickEvent);
    }

    if(this.node) {
      this.ontouchstart = (event) => {
        this.touchstart = event.touches[0].screenX;
      };
      this.node.object.on("touchstart", this.ontouchstart);

      this.ontouchend = (event) => {
        if(this.touchstart - event.changedTouches[0].screenX < ((window.devicePixelRatio) ? window.devicePixelRatio * -30 : -200)) this.set(this.index - 1);
        if(this.touchstart - event.changedTouches[0].screenX > ((window.devicePixelRatio) ? window.devicePixelRatio * 30 : 200)) this.set(this.index + 1);
      };
      this.node.object.on("touchend", this.ontouchend);
    }

    this.resize = compsoul.debounce(() => {
      this.responsive(true);
    }, 200);
    window.addEventListener("resize", this.resize);
  }

  remove() {
    if(this.prev) this.prev.object.off("click", this.prevClickEvent);
    if(this.next) this.next.object.off("click", this.nextClickEvent);
    if(this.node) this.node.object.off("touchstart", this.ontouchstart);
    if(this.node) this.node.object.off("touchend", this.ontouchend);

    window.removeEventListener("resize", this.resize);
  }

  build() {
    this.set(this.settings.first);
  }

  update(settings) {
    this.options(settings);
    this.remove();
    this.event();
    this.set((this.index) ? this.index : this.settings.first);
  }

  responsive() {
    let range;
    for (let key in this.settings.responsive) {
      if (window.innerWidth <= parseInt(key)) {
        this.options(this.default);
        this.update(this.settings.responsive[key]);
        range = true;
        return;
      }
    }
    if(!range) this.update(this.default);
  }

  options(settings) {
    if(!this.default && settings) this.default = Object.assign({}, Object.assign(this.settings, settings));
    if(settings) Object.assign(this.settings, settings);
    return this;
  }

  compsoul() {
    window.compsoul = window.compsoul || {};

    compsoul.throttle = compsoul.throttle || ((callback, delay) => {
      let throttle;
      return (...args) => {
        if (!throttle) {
          callback(...args);
          throttle = setTimeout(() => throttle = false, delay);
        }
      };
    })

    compsoul.debounce = compsoul.debounce || ((callback, delay) => {
      let timeout;
      return (...args) => {
        const that = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => callback.apply(that, args), delay);
      };
    })
  }

  init() {
    if(this.carousel.length === 0) return false;
    this.root();
    this.compsoul();
    this.classes(this.settings);
    this.build();
    this.event();
    this.responsive();
  };
}