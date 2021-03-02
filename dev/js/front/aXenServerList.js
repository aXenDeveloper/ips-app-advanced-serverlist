const COOKIE_NAME_TOGGLE = 'aXenServerList_widget_toggle';

document.querySelector('.aXenServerList_toggle').addEventListener('click', e => {
  e.preventDefault();
  const serverList = document.querySelector('.aXenServerList');
  serverList.classList.toggle('aXenServerList_hide');

  if (serverList.classList.contains('aXenServerList_hide')) {
    ips.utils.cookie.set(COOKIE_NAME_TOGGLE, 1, true);
  } else {
    const serverListContain = document.querySelector('.aXenServerList_ul');
    serverListContain.classList.add('ipsAnim');
    serverListContain.classList.add('ipsAnim_fade');
    serverListContain.classList.add('ipsAnim_in');

    setTimeout(() => {
      serverListContain.classList.remove('ipsAnim');
      serverListContain.classList.remove('ipsAnim_fade');
      serverListContain.classList.remove('ipsAnim_in');
    }, 450);

    ips.utils.cookie.unset(COOKIE_NAME_TOGGLE);
  }
});
