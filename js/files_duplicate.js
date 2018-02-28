/**
 * @author Lukas Koschmieder <lukas.koschmieder@rwth-aachen.de>
 * @author Alper Topaloglu <alper.topaloglu@rwth-aachen.de>
 * @copyright Copyright (c) 2017-18 RWTH Aachen University
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 */

(function(OC, OCA) {
$(document).ready(function() {
try {

// Register "Duplicate" action in Files app.
// Create custom "Duplicate" menu item. Define click callback.
OCA.Files.fileActions.registerAction({
	name          : 'duplicate',
	displayName   : 'Duplicate',
	mime          : 'all',
	permissions   : OC.PERMISSION_UPDATE,
	type          : OCA.Files.FileActions.TYPE_DROPDOWN, // TYPE_DROPDOWN | TYPE_INLINE
	order         : -50, // Place "Duplicate" item between "Details" and "Rename"
	icon          : OC.imagePath('core', 'actions/add'),
	actionHandler : function (fileName, fileObject) {
		OC.dialogs.info("Duplicating " + fileName + ". Please wait...", "Duplicate", function() {}, true);

		// Send duplicate request to PHP backend via HTTP POST
		request = $.ajax({
			type : "post",
			url  : OC.generateUrl("/apps/files_duplicate/duplicate"),
			data : { dirname: fileObject.dir, basename: fileName }
		});
		// Process backend response
		request.done(function (data) {
			$('.oc-dialog-content').each(function() { $(this).ocdialog('close'); }); // @TODO Close only above dialog
			if(data.success) {
				OC.Notification.showHtml(data.message, { timeout: 10 });
				FileList.add(data.info); // Refresh view
			} else {
				OC.dialogs.alert(data.message ? data.message : "Failed to duplicate file", 'Error', function() {}, true);
			}
		});
		request.fail(function (jqXHR, textStatus, errorThrown) {
			$('.oc-dialog-content').each(function() { $(this).ocdialog('close'); }); // @TODO Close only above dialog
			OC.dialogs.alert('Failed to duplicate file: ' + errorThrown, 'Connection Failure', function() {}, true);
		});
	}
});

} catch(e) {}
});
})(OC, OCA);
