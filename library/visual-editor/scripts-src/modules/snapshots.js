define(['jquery', 'knockout'], function($, ko) {

	var veI18n = (typeof Padma !== 'undefined' && Padma.i18nVE) ? Padma.i18nVE : {};
	var t = function(key, fallback) {
		return (typeof veI18n[key] === 'string' && veI18n[key].length) ? veI18n[key] : fallback;
	};

	var snapshots = {
		init: function() {

			snapshots.bind();
			snapshots.setupViewModel();

		},

		setupViewModel: function() {

			Padma.viewModels.snapshots = {
				snapshots: ko.observableArray(Padma.snapshots),
				formatSnapshotDatetime: function(datetime) {

					var datetimeFrags = datetime.split(/[- :]/);

					return new Date(Date.UTC(datetimeFrags[0], datetimeFrags[1] - 1, datetimeFrags[2], datetimeFrags[3], datetimeFrags[4], datetimeFrags[5])).toLocaleString();

				},
				rollbackToSnapshot: function(data, event) {

					if ( !confirm(t('confirmRollbackSnapshot', 'Bist du sicher, dass du zurueckrollen willst?\n\nDu verlierst alles zwischen diesem Snapshot und jetzt, wenn du nicht vorher einen neuen Snapshot speicherst.')) )
						return false;

					var button = $(event.target);

					if ( button.attr('disabled') )
						return false;

					/* Disable button temporarily */
					button.attr('disabled', true);
					button.addClass('button-depressed');
					button.text(t('snapshotRollingBack', 'Rolle zurueck..'));

					/* Rollback */
					$.post(Padma.ajaxURL, {
						security: Padma.security,
						action: 'padma_visual_editor',
						method: 'rollback_to_snapshot',
						layout: Padma.viewModels.layoutSelector.currentLayout(),
						snapshot_id: data.id,
						mode: Padma.mode
					}, function(response) {

						if ( typeof response.error != 'undefined' )
							return;

						showNotification({
							id: 'rolled-back-successfully',
							message: t('snapshotRollbackSuccess', 'Snapshot erfolgreich wiederhergestellt.<br /><br /><strong>Visual Editor wird in 3 Sekunden neu geladen</strong>.'),
							success: true
						});

						button.text(t('snapshotRolledBack', 'Zurueckgerollt!'));

						/* Reload the Visual Editor */
						setTimeout(function() {
							allowVEClose();
							document.location.reload(true);
						}, 1000);

					});

				},
				deleteSnapshot: function(data, event) {

					if ( !confirm(t('confirmDeleteSnapshot', 'Bist du sicher, dass du diesen Snapshot loeschen willst?\n\nDas kannst du nicht rueckgaengig machen und auch nicht ueber einen anderen Snapshot wiederherstellen.')) )
						return false;

					var button = $(event.target);

					if ( button.hasClass('deletion-in-progress') )
						return false;

					/* Disable button temporarily */
					button.addClass('deletion-in-progress');

					/* Delete snapshot */
					$.post(Padma.ajaxURL, {
						security: Padma.security,
						action: 'padma_visual_editor',
						method: 'delete_snapshot',
						layout: Padma.viewModels.layoutSelector.currentLayout(),
						snapshot_id: data.id,
						mode: Padma.mode
					}, function (response) {

						if ( typeof response.error != 'undefined' )
							return;

						showNotification({
							id: 'deleted-snapshot-successfully',
							message: t('snapshotDeletedSuccess', 'Snapshot erfolgreich geloescht.'),
							success: true
						});

						Padma.viewModels.snapshots.snapshots.remove(data);

					});


				},
				saveSnapshot: function(data, event) {

					var button = $(event.target);

					if ( button.attr('disabled') )
						return false;

					/* Disable button temporarily */
					button.attr('disabled', true);
					button.text('Snapshot wird gespeichert...');

					/* Add the snapshot */
					button.siblings('.spinner').show();

					/* Prompt for comments about snapshot */
					var snapshotComments = prompt("(Optional)\n\nGib einen Namen oder eine Beschreibung fuer die Aenderungen in diesem Snapshot ein.");

					$.post(Padma.ajaxURL, {
						security: Padma.security,
						action: 'padma_visual_editor',
						method: 'save_snapshot',
						layout: Padma.viewModels.layoutSelector.currentLayout(),
						mode: Padma.mode,
						snapshot_comments: snapshotComments
					}, function(response) {

						if ( typeof response.timestamp == 'undefined' )
							return;

						showNotification({
							id: 'snapshot-saved',
							message: 'Snapshot gespeichert.',
							success: true
						});

						Padma.viewModels.snapshots.snapshots.unshift({
							id: response.id,
							timestamp: response.timestamp,
							comments: response.comments
						});

						button.text('Snapshot speichern');
						button.removeAttr('disabled');
						button.siblings('.spinner').hide();

					});

				}
			}

			$(document).ready(function() {
				ko.applyBindings(Padma.viewModels.snapshots, $('#box-snapshots').get(0));
			});

		},

		bind: function() {


		}
	}


	return snapshots;

});