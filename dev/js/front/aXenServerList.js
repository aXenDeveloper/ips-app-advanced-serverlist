const COOKIE_NAME_TOGGLE = 'aXenServerList_widget_toggle';

document.querySelector('.aXenServerList_toggle').addEventListener('click', () => {
  const serverList = document.querySelector('.aXenServerList');
  serverList.classList.toggle('aXenServerList_hide');

  if (serverList.classList.contains('aXenServerList_hide')) {
    ips.utils.cookie.set(COOKIE_NAME_TOGGLE, 1, true);
  } else {
    ips.utils.cookie.unset(COOKIE_NAME_TOGGLE);
  }
});
