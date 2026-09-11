(function () {
  'use strict';

  var config = window.BinShihonSecurityLocation || {};
  var storageKey = 'bsl-location-choice-' + (config.userId || '0');

  function postLocation(payload) {
    var data = new FormData();

    data.append('action', 'bsl_save_location');
    data.append('nonce', config.nonce || '');

    Object.keys(payload).forEach(function (key) {
      data.append(key, payload[key]);
    });

    return fetch(config.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: data,
    });
  }

  function closePrompt(prompt) {
    if (prompt && prompt.parentNode) {
      prompt.parentNode.removeChild(prompt);
    }
  }

  function buildPrompt() {
    if (!navigator.geolocation || localStorage.getItem(storageKey)) {
      return;
    }

    var prompt = document.createElement('div');
    prompt.className = 'bsl-location-prompt';
    prompt.innerHTML = [
      '<div class="bsl-location-prompt__content">',
      '<strong>' + (config.prompt && config.prompt.title ? config.prompt.title : 'Secure this login?') + '</strong>',
      '<p>' + (config.prompt && config.prompt.description ? config.prompt.description : 'Allow location for account security.') + '</p>',
      '</div>',
      '<div class="bsl-location-prompt__actions">',
      '<button type="button" class="bsl-location-prompt__allow">' + (config.prompt && config.prompt.allow ? config.prompt.allow : 'Allow location') + '</button>',
      '<button type="button" class="bsl-location-prompt__dismiss">' + (config.prompt && config.prompt.dismiss ? config.prompt.dismiss : 'Not now') + '</button>',
      '</div>',
    ].join('');

    document.body.appendChild(prompt);

    prompt.querySelector('.bsl-location-prompt__allow').addEventListener('click', function () {
      localStorage.setItem(storageKey, 'allowed');

      navigator.geolocation.getCurrentPosition(
        function (position) {
          postLocation({
            status: 'granted',
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            accuracy: position.coords.accuracy || '',
          });
          closePrompt(prompt);
        },
        function () {
          postLocation({ status: 'denied' });
          closePrompt(prompt);
        },
        {
          enableHighAccuracy: true,
          timeout: 15000,
          maximumAge: 0,
        }
      );
    });

    prompt.querySelector('.bsl-location-prompt__dismiss').addEventListener('click', function () {
      localStorage.setItem(storageKey, 'dismissed');
      postLocation({ status: 'dismissed' });
      closePrompt(prompt);
    });
  }

  document.addEventListener('DOMContentLoaded', buildPrompt);
})();
