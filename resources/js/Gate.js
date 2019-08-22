export default class Gate {

    constructor(user) {
        this.user = user;
    }
    isAdmin() {
        return this.user.roles == 'Admin';
    }
    isUser() {
        return this.user.roles == 'User';

    }
    isAuthor() {
        return this.user.roles == 'Author';

    }

}
