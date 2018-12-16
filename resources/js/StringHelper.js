
export default class StringHelper {

    // turns out I dont need this after all...
    isValidHostName (what) {
        if (typeof what === "string") {
            const host_name_valid_pattern = /^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/
            const host_name_length_pattern = /^.{1,63}$/
            return host_name_valid_pattern.test(what) && host_name_length_pattern.test(what);
        }
        return false;
    }

}