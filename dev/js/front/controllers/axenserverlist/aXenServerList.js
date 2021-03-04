const COOKIE_AXENSERVERLIST_HIDE = 'aXenServerList_widget_hide';
const COOKIE_AXENSERVERLIST_SCROLL = 'aXenServerList_widget_scroll';

const serverList = document.querySelector('.aXenServerList');

// Toogle hide
document.querySelector('.aXenServerList_toggle_hide')?.addEventListener('click', e => {
  e.preventDefault();
  serverList.classList.toggle('aXenServerList_hide');

  if (serverList.classList.contains('aXenServerList_hide')) {
    ips.utils.cookie.set(COOKIE_AXENSERVERLIST_HIDE, 1, true);
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

    ips.utils.cookie.unset(COOKIE_AXENSERVERLIST_HIDE);
  }
});

// Toogle scroll
document.querySelector('.aXenServerList_toggle_scroll')?.addEventListener('click', e => {
  e.preventDefault();
  serverList.classList.toggle('aXenServerList_scroll');

  if (serverList.classList.contains('aXenServerList_scroll')) {
    ips.utils.cookie.set(COOKIE_AXENSERVERLIST_SCROLL, 1, true);
  } else ips.utils.cookie.unset(COOKIE_AXENSERVERLIST_SCROLL);

  ips.utils.cookie.set(`${COOKIE_AXENSERVERLIST_SCROLL}_manual`, 1, true);
});
