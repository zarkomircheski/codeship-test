const cookies = {
  init() {},

  create_cookie(name, value, days, customDate) {
    let expires = '';
    if (days) {
      const date = new Date();
      date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
      expires = `; expires=${date.toUTCString()}`;
    } else if (customDate) {
      expires = `; expires=${customDate.toUTCString()}`;
    }
    document.cookie = `${name}=${value}${expires}; path=/`;
  },

  read_cookie(name) {
    const nameEQ = `${name}=`;
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  },

  erase_cookie(name) {
    this.create_cookie(name, '', -1);
  },
};

export default cookies;
