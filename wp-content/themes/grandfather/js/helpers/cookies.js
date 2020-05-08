const cookies = {
  init() {},
  /**
   * Get any cookie that has been set to the user
   *
   * @param {string} name - name of the cookie you want returned
   */
  getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length >= 2) return parts.pop().split(';').shift();
  },
};

export default cookies;
