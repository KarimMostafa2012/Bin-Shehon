(function () {
  function setActive(roller, nextIndex) {
    var slides = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-slide]'));
    var media = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-media]'));
    var dots = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-dot]'));
    var count = slides.length;

    if (!count) {
      return;
    }

    var index = ((nextIndex % count) + count) % count;
    var angle = index * -90;

    roller.dataset.sprIndex = String(index);
    roller.style.setProperty('--spr-rotation', angle + 'deg');

    slides.forEach(function (slide, slideIndex) {
      slide.classList.toggle('is-active', slideIndex === index);
    });

    media.forEach(function (item, itemIndex) {
      item.classList.toggle('is-active', itemIndex === index);
    });

    dots.forEach(function (dot, dotIndex) {
      dot.classList.toggle('is-active', dotIndex === index);
    });
  }

  function initRoller(roller) {
    var next = roller.querySelector('[data-spr-next]');
    var prev = roller.querySelector('[data-spr-prev]');

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
