(function ($) {
  function reindexRows($box) {
    $box.find('[data-spr-row]').each(function (index) {
      $(this)
        .find('[name]')
        .each(function () {
          this.name = this.name.replace(/spr_items\[[^\]]+\]/, 'spr_items[' + index + ']');
        });

      reindexBullets($(this));
    });
  }

  function reindexBullets($row) {
    $row.find('[data-spr-bullet]').each(function (index) {
      $(this)
        .find('[name]')
        .each(function () {
          this.name = this.name.replace(/\[bullets\]\[[^\]]+\]/, '[bullets][' + index + ']');
        });
    });
  }

  function setPreview($control, attachment) {
    $control.find('[data-spr-image-id]').val(attachment.id || '');
    $control
      .find('[data-spr-select-image]')
      .html(attachment.url ? '<img src="' + attachment.url + '" alt="">' : '<span>Choose image</span>');
  }

  $(function () {
    $('[data-spr-admin]').each(function () {
      var $box = $(this);

      $box.on('click', '[data-spr-add]', function () {
        var template = $box.find('[data-spr-template]').html();
        var index = $box.find('[data-spr-row]').length;

        $box.find('[data-spr-rows]').append(template.replace(/__INDEX__/g, index));
      });

      $box.on('click', '[data-spr-remove-row]', function () {
        var $rows = $box.find('[data-spr-row]');

        if ($rows.length === 1) {
          $rows.find('input[type="text"], textarea, input[type="hidden"]').val('');
          $rows.find('[data-spr-image-control] [data-spr-select-image]').html('<span>Choose image</span>');
          $rows.find('[data-spr-bullet]').remove();
          return;
        }

        $(this).closest('[data-spr-row]').remove();
        reindexRows($box);
      });

      $box.on('click', '[data-spr-remove-image]', function () {
        setPreview($(this).closest('[data-spr-image-control]'), {});
      });

      $box.on('click', '[data-spr-select-image]', function () {
        var $control = $(this).closest('[data-spr-image-control]');
        var frame = wp.media({
          title: 'Choose roller image',
          button: {
            text: 'Use image',
          },
          multiple: false,
        });

        frame.on('select', function () {
          var attachment = frame.state().get('selection').first().toJSON();
          setPreview($control, attachment);
        });

        frame.open();
      });

      $box.on('click', '[data-spr-add-bullet]', function () {
        var $row = $(this).closest('[data-spr-row]');
        var template = $row.find('[data-spr-bullet-template]').html();
        var index = $row.find('[data-spr-bullet]').length;

        $row.find('[data-spr-bullet-list]').append(template.replace(/__BULLET_INDEX__/g, index));
      });

      $box.on('click', '[data-spr-remove-bullet]', function () {
        var $row = $(this).closest('[data-spr-row]');

        $(this).closest('[data-spr-bullet]').remove();
        reindexBullets($row);
      });
    });
  });
})(jQuery);
