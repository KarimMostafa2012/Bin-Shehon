(function () {
  function wrappedIndex(index, count) {
    return ((index % count) + count) % count;
  }

  function setActive(roller, nextIndex) {
    var slides = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-slide]'));
    var media = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-media]'));
    var dots = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-dot]'));
    var count = slides.length;

    if (!count) {
      return;
    }

    var index = wrappedIndex(nextIndex, count);
    var prevIndex = wrappedIndex(index - 1, count);
    var nextItemIndex = wrappedIndex(index + 1, count);

    roller.dataset.sprIndex = String(index);

    slides.forEach(function (slide, slideIndex) {
      slide.classList.toggle('is-active', slideIndex === index);
    });

    media.forEach(function (item, itemIndex) {
      item.classList.remove('is-active', 'is-prev', 'is-next', 'is-hidden');

      if (itemIndex === index) {
        item.classList.add('is-active');
      } else if (count > 2 && itemIndex === prevIndex) {
        item.classList.add('is-prev');
      } else if (count > 1 && itemIndex === nextItemIndex) {
        item.classList.add('is-next');
      } else {
        item.classList.add('is-hidden');
      }
    });

    dots.forEach(function (dot, dotIndex) {
      dot.classList.toggle('is-active', dotIndex === index);
    });
  }

  function initRoller(roller) {
    var next = roller.querySelector('[data-spr-next]');
    var prev = roller.querySelector('[data-spr-prev]');

    setActive(roller, Number(roller.dataset.sprIndex || 0));

    if (next) {
      next.addEventListener('click', function () {
        setActive(roller, Number(roller.dataset.sprIndex || 0) + 1);
      });
    }

    if (prev) {
      prev.addEventListener('click', function () {
        setActive(roller, Number(roller.dataset.sprIndex || 0) - 1);
      });
    }

    roller.querySelectorAll('[data-spr-dot]').forEach(function (dot) {
      dot.addEventListener('click', function () {
        setActive(roller, Number(dot.dataset.sprDot || 0));
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-spr-roller]').forEach(initRoller);
  });
})();
