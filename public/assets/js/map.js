!(function (t) {
    Array.prototype.forEach ||
        (t.forEach =
            t.forEach ||
            function (t, o) {
                for (var e = 0, a = this.length; e < a; e++) e in this && t.call(o, this[e], e, this);
            });
})(Array.prototype);

console.log(document.getElementById("map"))
for (var key in ((mapObject = new google.maps.Map(document.getElementById("map"), mapOptions)), markersData))
    markersData[key].forEach(function (t) {
        (marker = new google.maps.Marker({ position: new google.maps.LatLng(t.location_latitude, t.location_longitude), map: mapObject, icon: "assets/img/marker.png" })),
            void 0 === markers[key] && (markers[key] = []),
            markers[key].push(marker),
            google.maps.event.addListener(marker, "click", function () {
                closeInfoBox(), getInfoBox(t).open(mapObject, this), mapObject.setCenter(new google.maps.LatLng(t.location_latitude, t.location_longitude));
            });
    });
function hideAllMarkers() {
    for (var t in markers)
        markers[t].forEach(function (t) {
            t.setMap(null);
        });
}
function closeInfoBox() {
    $("div.infoBox").remove();
}
function getInfoBox(t) {
    return new InfoBox({
        content:
            '<div class="map-popup-wrap"><div class="map-popup"><div class="_RentUP_proprty_grid"><div class="_RentUP_prt_grid_thumb"><a href="' +
            t.locationURL +
            '"><img src="' +
            t.locationImg +
            '" class="img-fluid" alt="' + (t.propertyname ? String(t.propertyname).replace(/"/g, '&quot;') : 'اقامتگاه') + '" loading="lazy" decoding="async" /></a><div class="rhomy_abs_caption"><h4 class="rhomy_pr_name">' +
            t.propertypricebed +
            '</h4></div></div><div class="_RentUP_prt_grid_caption"><div class="_RentUP_prt_head"><h5 class="_RentUP_prt_title">' +
            t.propertyname +
            '</h5><span class="_RentUP_prt_location"><i class="ti-location-pin ml-1"></i>' +
            t.propertylocation +
            '</span></div><div class="_RentUP_prt_bot"><div class="_RentUP_prt_bot_flex"></div><div class="_RentUP_prt_bot_left"><a href="' +
            t.contactURL +
            '" class="mp_rhomy_btn">جزئیات</a></div></div></div></div></div></div>',
        disableAutoPan: !1,
        maxWidth: 0,
        pixelOffset: new google.maps.Size(10, 92),
        closeBoxMargin: "",
        closeBoxURL: "assets/img/close.png",
        isHidden: !1,
        alignBottom: !0,
        pane: "floatPane",
        enableEventPropagation: !0,
    });
}
function onHtmlClick(t, o) {
    google.maps.event.trigger(markers[t][o], "click");
}
new MarkerClusterer(mapObject, markers[key]);
