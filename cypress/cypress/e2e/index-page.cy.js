/// <reference types="Cypress" />

import { v4 as uuidv4 } from 'uuid';

const signIn = function () {
  cy.visit('localhost')
  cy.contains('Products').click()
  cy.get('input#username-field').type('root')
  cy.get('input#password-field').type('sUP3R53CR3T#')
  cy.get('button[type=submit').click()
}

const createProduct = function (isActive = true) {
  cy.contains('Products').click()
  cy.contains('Create New Product').click()
  const randomName = uuidv4()
  cy.get('input#name-field').type(randomName)
  cy.get('input#sku-field').type(randomName)
  cy.get('input#price-field').type(123)
  cy.get('input#stock-field').type(10)
  if (isActive) {
    cy.get('input#active-checkbox').check()
  }
  cy.contains('Save and Close').click()

  return randomName
}

describe('index page', () => {
  it('highlights the stock amount in red if it is <= 3', () => {
    cy.intercept('/API/V1/popular-products', { fixture: 'popular-products.json' })
    
    cy.visit('localhost')

    cy.get('td[data-test-stock-id="1"]')
      .should('have.css', 'color')
      .should('equal', 'rgb(255, 0, 0)')
    
    cy.get('td[data-test-stock-id="2"]')
      .should('have.css', 'color')
      .should('equal', 'rgb(255, 0, 0)')
    
    cy.get('td[data-test-stock-id="3"]')
      .should('have.css', 'color')
      .should('equal', 'rgb(0, 0, 0)')
  })

  it('only shows active products on the first page', () => {
    signIn()
    
    const activeProductName = createProduct()
    const inActiveProductName = createProduct(false)
    
    cy.visit('localhost')
    cy.get('body').should('contain.text', activeProductName)
    cy.get('body').should('not.contain.text', inActiveProductName)
  })
})