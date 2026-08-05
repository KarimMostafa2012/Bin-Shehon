(function () {
  function wrappedIndex(index, count) {
    return ((index % count) + count) % count;
  }

  function isEnglish() {
    var lang = document.documentElement.lang || '';
    return lang.toLowerCase().indexOf('en') === 0;
  }

  function setActive(roller, nextIndex) {
    var slides = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-slide]'));
    var wheels = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-wheel]'));
    var dots = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-dot]'));
    var count = slides.length;

    if (!count) {
      return;
    }

    var index = wrappedIndex(nextIndex, count);
    var activeWheel = Math.floor(index / 4);
    var activeQuarter = index % 4;
    var direction = isEnglish() ? -90 : 90;

    roller.dataset.sprIndex = String(index);

    slides.forEach(function (slide, slideIndex) {
      slide.classList.toggle('is-active', slideIndex === index);
    });

    wheels.forEach(function (wheel, wheelIndex) {
      var isActiveWheel = wheelIndex === activeWheel;
      var distance = wrappedIndex(activeWheel - wheelIndex, wheels.length);
      var quarter = isActiveWheel ? activeQuarter : 0;

      wheel.classList.toggle('is-active', isActiveWheel);
      wheel.classList.toggle('is-under', !isActiveWheel && distance === 1);
      wheel.classList.toggle('is-hidden', !isActiveWheel && distance !== 1);
      wheel.style.setProperty('--spr-wheel-rotation', quarter * direction + 'deg');
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
