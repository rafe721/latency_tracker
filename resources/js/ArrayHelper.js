
export default class ArrayHelper {

    // turns out I dont need this after all...
    isArray (what) {
        return Object.prototype.toString.call(what) === "[object Array]";
    }

}