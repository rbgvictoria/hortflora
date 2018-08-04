const snakeCaseToTitleCase = function(text) {
    let result = text.replace( /([A-Z])/g, " $1" );
    return result.charAt(0).toUpperCase() + result.slice(1);
};

export { snakeCaseToTitleCase };

const camelCaseToSnakeCase = function(text) {
    return text.replace(/([A-Z])/g, function($1){return "_"+$1.toLowerCase();});
}

export { camelCaseToSnakeCase };

const parseFq = function(item) {
    if (typeof item !== 'string') {
        return false;
    }

    let label = '';
    let values = [];
    let filter = {};
    if (item.substr(0, 1) === '(' && item.substr(item.length-1, 1) === ')') {
        const substr = item.substr(1, item.length-2);
        const bits = substr.split(/ or /i);
        $.each(bits, (i, bit) => {
            if (i === 0) {
                label = bit.substr(0, bit.indexOf(':'));
            }
            values.push(bit.substr(bit.indexOf(':') + 1));
        })
        filter = {
            label: label,
            value: values.join()
        };
    }
    else {
        let values = [];
        let valueStr = item.substr(item.indexOf(':')+1);
        if (valueStr.charAt(0) === '(' && valueStr.charAt(valueStr.length-1) === ')') {
            valueStr = valueStr.substr(1, valueStr.length-2);
            values = valueStr.split(/ or /i);
        }
        else {
            values.push(valueStr);
        }
        filter = {
            label: item.substr(0, item.indexOf(':')),
            value: values.join()
        };
    }
    return filter;
};

export { parseFq };
