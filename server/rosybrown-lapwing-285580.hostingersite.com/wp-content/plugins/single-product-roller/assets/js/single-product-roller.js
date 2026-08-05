(function () {
  var ROTATION_MS = 760;

  function wrappedIndex(index, count) {
    return ((index % count) + count) % count;
  }

  function isEnglish() {
    var lang = document.documentElement.lang || '';
    return lang.toLowerCase().indexOf('en') === 0;
  }

  function getDirection() {
    return isEnglish() ? -90 : 90;
  }

  function clearBridge(wheels) {
    wheels.forEach(function (wheel) {
      wheel.classList.remove(
        'is-bridging',
        'is-bridge-under',
        'is-bridge-slot-0',
        'is-bridge-slot-1',
        'is-bridge-slot-2',
        'is-bridge-slot-3'
      );
    });
  }

  function renderState(roller, index) {
    var slides = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-slide]'));
    var wheels = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-wheel]'));
    var dots = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-dot]'));
    var count = slides.length;

    if (!count) {
      return;
    }

    var activeWheel = Math.floor(index / 4);
    var activeQuarter = index % 4;

    roller.dataset.sprIndex = String(index);

    slides.forEach(function (slide, slideIndex) {
      slide.classList.toggle('is-active', slideIndex === index);
    });

    clearBridge(wheels);

    wheels.forEach(function (wheel, wheelIndex) {
      var isActiveWheel = wheelIndex === activeWheel;
      var distance = wrappedIndex(activeWheel - wheelIndex, wheels.length);
      var quarter = isActiveWheel ? activeQuarter : 0;

      wheel.classList.toggle('is-active', isActiveWheel);
      wheel.classList.toggle('is-under', !isActiveWheel && distance === 1);
      wheel.classList.toggle('is-hidden', !isActiveWheel && distance !== 1);
      wheel.style.setProperty('--spr-wheel-rotation', quarter * getDirection() + 'deg');
    });

    dots.forEach(function (dot, dotIndex) {
      dot.classList.toggle('is-active', dotIndex === index);
    });
  }

  function bridgeToNextWheel(roller, currentIndex, targetIndex) {
    var wheels = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-wheel]'));
    var currentWheel = Math.floor(currentIndex / 4);
    var targetWheel = Math.floor(targetIndex / 4);
    var topWheel = wheels[currentWheel];
    var underWheel = wheels[targetWheel];
    var bridgeQuarter = (currentIndex % 4) + 1;
    var bridgeSlot = bridgeQuarter % 4;

    if (!topWheel || !underWheel) {
      renderState(roller, targetIndex);
      return;
    }

    roller.dataset.sprTransitioning = '1';
    clearBridge(wheels);

    wheels.forEach(function (wheel) {
      wheel.classList.remove('is-active', 'is-under', 'is-hidden', 'is-bridge-under');
      wheel.classList.add('is-hidden');
    });

    underWheel.classList.remove('is-hidden');
    underWheel.classList.add('is-bridge-under');
    underWheel.style.setProperty('--spr-wheel-rotation', '0deg');

    topWheel.classList.remove('is-hidden');
    topWheel.classList.add('is-active', 'is-bridging', 'is-bridge-slot-' + bridgeSlot);
    topWheel.style.setProperty('--spr-wheel-rotation', bridgeQuarter * getDirection() + 'deg');

    window.setTimeout(function () {
      renderState(roller, targetIndex);
      roller.dataset.sprTransitioning = '0';
    }, ROTATION_MS);
  }

  function setActive(roller, nextIndex) {
    var slides = Array.prototype.slice.call(roller.querySelectorAll('[data-spr-slide]'));
    var count = slides.length;

    if (!count || roller.dataset.sprTransitioning === '1') {
      return;
    }

    var currentIndex = wrappedIndex(Number(roller.dataset.sprIndex || 0), count);
    var targetIndex = wrappedIndex(nextIndex, count);
    var isForwardOne = targetIndex === wrappedIndex(currentIndex + 1, count);
    var crossesWheelForward = isForwardOne && Math.floor(currentIndex / 4) !== Math.floor(targetIndex / 4);

    if (crossesWheelForward) {
      bridgeToNextWheel(roller, currentIndex, targetIndex);
      return;
    }

    renderState(roller, targetIndex);
  }

  function initRoller(roller) {
    var next = roller.querySelector('[data-spr-next]');
    var prev = roller.querySelector('[data-spr-prev]');

    roller.dataset.sprTransitioning = '0';
    renderState(roller, Number(roller.dataset.sprIndex || 0));

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
