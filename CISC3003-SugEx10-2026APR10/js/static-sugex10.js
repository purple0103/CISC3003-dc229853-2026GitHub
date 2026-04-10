(function () {
  'use strict';

  var customerTableBody = document.getElementById('customers-table-body');
  var customerDetailsCard = document.getElementById('customer-details-card');
  var ordersTableBody = document.getElementById('orders-table-body');
  var missingCoverPath = 'images/tinysquare/missing.jpg';

  function getSelectedCustomerId() {
    var params = new URLSearchParams(window.location.search);
    var value = params.get('customer');
    if (!value) {
      return null;
    }

    var parsed = Number(value);
    return Number.isFinite(parsed) ? parsed : null;
  }

  function decodeBuffer(buffer) {
    var utf8 = new TextDecoder('utf-8').decode(buffer);
    if (utf8.indexOf('\uFFFD') === -1) {
      return utf8;
    }

    var encodings = ['windows-1250', 'windows-1252', 'iso-8859-2'];
    for (var i = 0; i < encodings.length; i += 1) {
      try {
        var decoded = new TextDecoder(encodings[i]).decode(buffer);
        if (decoded.indexOf('\uFFFD') === -1) {
          return decoded;
        }
      } catch (error) {
        continue;
      }
    }

    return utf8;
  }

  function fetchText(path) {
    return fetch(path).then(function (response) {
      if (!response.ok) {
        throw new Error('Failed to load ' + path);
      }
      return response.arrayBuffer();
    }).then(decodeBuffer);
  }

  function parseCustomers(text) {
    return text.split(/\r?\n/).filter(Boolean).map(function (line) {
      var parts = line.trim().split(';');
      var sales = parts[11].split(',').map(function (value) {
        return Number(String(value).trim());
      });

      return {
        id: Number(parts[0]),
        firstName: parts[1].trim(),
        lastName: parts[2].trim(),
        fullName: parts[1].trim() + ' ' + parts[2].trim(),
        email: parts[3].trim(),
        university: parts[4].trim(),
        address: parts[5].trim(),
        city: parts[6].trim(),
        state: parts[7].trim(),
        country: parts[8].trim(),
        postal: parts[9].trim(),
        phone: parts[10].trim(),
        sales: sales,
        salesRaw: sales.join(',')
      };
    });
  }

  function parseOrders(text) {
    return text.split(/\r?\n/).filter(Boolean).map(function (line) {
      var parts = line.trim().split(',').map(function (item) {
        return item.trim();
      });

      return {
        orderId: Number(parts.shift()),
        customerId: Number(parts.shift()),
        isbn: parts.shift(),
        category: parts.pop(),
        title: parts.join(', ')
      };
    });
  }

  function createCell(content, className) {
    var cell = document.createElement('td');
    if (className) {
      cell.className = className;
    }

    if (content instanceof Node) {
      cell.appendChild(content);
    } else {
      cell.textContent = content;
    }

    return cell;
  }

  function renderCustomers(customers, selectedCustomerId) {
    customerTableBody.innerHTML = '';

    customers.forEach(function (customer) {
      var row = document.createElement('tr');
      if (customer.id === selectedCustomerId) {
        row.className = 'selected-customer-row';
      }

      var link = document.createElement('a');
      link.className = 'customer-link';
      link.href = 'index.html?customer=' + customer.id;
      link.textContent = customer.fullName;

      var sparkline = document.createElement('span');
      sparkline.className = 'inlinesparkline';
      sparkline.textContent = customer.salesRaw;

      row.appendChild(createCell(link, 'mdl-data-table__cell--non-numeric'));
      row.appendChild(createCell(customer.university, 'mdl-data-table__cell--non-numeric'));
      row.appendChild(createCell(customer.city, 'mdl-data-table__cell--non-numeric'));
      row.appendChild(createCell(sparkline));
      customerTableBody.appendChild(row);
    });

    $('.inlinesparkline').sparkline('html', {
      type: 'bar',
      barColor: '#5c6bc0',
      negBarColor: '#ef5350',
      height: '30px',
      barWidth: 6,
      barSpacing: 2
    });
  }

  function appendDetailLine(label, value) {
    var paragraph = document.createElement('p');
    var strong = document.createElement('strong');
    strong.textContent = label + ':';
    paragraph.appendChild(strong);
    paragraph.appendChild(document.createTextNode(' ' + value));
    return paragraph;
  }

  function renderCustomerDetails(customer, selectedCustomerId) {
    customerDetailsCard.innerHTML = '';

    if (customer) {
      var heading = document.createElement('h3');
      heading.textContent = customer.fullName;
      customerDetailsCard.appendChild(heading);

      var details = document.createElement('div');
      details.className = 'customer-details';
      details.appendChild(appendDetailLine('Email', customer.email));
      details.appendChild(appendDetailLine('University', customer.university));
      details.appendChild(appendDetailLine('Address', customer.address));
      details.appendChild(appendDetailLine('City', customer.city));

      if (customer.state) {
        details.appendChild(appendDetailLine('State', customer.state));
      }

      details.appendChild(appendDetailLine('Country', customer.country));

      if (customer.postal) {
        details.appendChild(appendDetailLine('Postal Code', customer.postal));
      }

      details.appendChild(appendDetailLine('Phone', customer.phone));
      customerDetailsCard.appendChild(details);
      return;
    }

    var defaultHeading = document.createElement('h3');
    defaultHeading.textContent = selectedCustomerId ? 'Requested customer not found' : 'Customer Name here';
    customerDetailsCard.appendChild(defaultHeading);

    var message = document.createElement('p');
    message.className = 'empty-state';
    message.textContent = selectedCustomerId
      ? 'The selected customer id does not match any record in the data file.'
      : 'Select a customer name from the table to view the full record.';
    customerDetailsCard.appendChild(message);
  }

  function renderOrders(orders, selectedCustomerId) {
    ordersTableBody.innerHTML = '';

    if (!selectedCustomerId) {
      var initialRow = document.createElement('tr');
      initialRow.appendChild(createCell('Select a customer to view order details.', 'mdl-data-table__cell--non-numeric empty-state'));
      initialRow.firstChild.colSpan = 3;
      ordersTableBody.appendChild(initialRow);
      return;
    }

    if (!orders.length) {
      var emptyRow = document.createElement('tr');
      emptyRow.appendChild(createCell('No order information found for this customer.', 'mdl-data-table__cell--non-numeric empty-state'));
      emptyRow.firstChild.colSpan = 3;
      ordersTableBody.appendChild(emptyRow);
      return;
    }

    orders.forEach(function (order) {
      var row = document.createElement('tr');
      var image = document.createElement('img');
      image.className = 'book-cover';
      image.src = 'images/tinysquare/' + order.isbn + '.jpg';
      image.alt = order.title;
      image.onerror = function () {
        image.onerror = null;
        image.src = missingCoverPath;
      };

      row.appendChild(createCell(image, 'mdl-data-table__cell--non-numeric'));
      row.appendChild(createCell(order.isbn, 'mdl-data-table__cell--non-numeric'));
      row.appendChild(createCell(order.title, 'mdl-data-table__cell--non-numeric'));
      ordersTableBody.appendChild(row);
    });
  }

  function renderError(message) {
    customerTableBody.innerHTML = '';
    customerDetailsCard.innerHTML = '';
    ordersTableBody.innerHTML = '';

    var customerRow = document.createElement('tr');
    customerRow.appendChild(createCell(message, 'mdl-data-table__cell--non-numeric empty-state'));
    customerRow.firstChild.colSpan = 4;
    customerTableBody.appendChild(customerRow);

    var detailsHeading = document.createElement('h3');
    detailsHeading.textContent = 'Unable to load data';
    customerDetailsCard.appendChild(detailsHeading);

    var detailsMessage = document.createElement('p');
    detailsMessage.className = 'empty-state';
    detailsMessage.textContent = message;
    customerDetailsCard.appendChild(detailsMessage);

    var orderRow = document.createElement('tr');
    orderRow.appendChild(createCell(message, 'mdl-data-table__cell--non-numeric empty-state'));
    orderRow.firstChild.colSpan = 3;
    ordersTableBody.appendChild(orderRow);
  }

  function initializePage(data) {
    var customers = parseCustomers(data[0]);
    var orders = parseOrders(data[1]);
    var selectedCustomerId = getSelectedCustomerId();
    var selectedCustomer = customers.find(function (customer) {
      return customer.id === selectedCustomerId;
    }) || null;

    var matchingOrders = selectedCustomer
      ? orders.filter(function (order) {
          return order.customerId === selectedCustomer.id;
        })
      : [];

    renderCustomers(customers, selectedCustomerId);
    renderCustomerDetails(selectedCustomer, selectedCustomerId);
    renderOrders(matchingOrders, selectedCustomerId);
  }

  Promise.all([
    fetchText('data/customers.txt'),
    fetchText('data/orders.txt')
  ]).then(initializePage).catch(function (error) {
    renderError(error.message);
  });
}());
