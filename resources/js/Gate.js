export default class Gate {

    constructor(user) {
        this.user = user;
    }
    isAdmin() {
        return this.user.role == 'Admin';

    }
    isUser() {
        return this.user.role == 'User';

    }
    isAuthor() {
        return this.user.role == 'Author';

    }
}
