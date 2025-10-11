import React from "react";
import styles from "../portfolio.module.css";

export default function Contact() {
  return (
    <section className={styles.contact}>
      <h3>Contact</h3>
      <p>
        Want to work together or ask a question? Reach out at
        <a href="mailto:magnomarithea157@gmail.com"> magnomarithea157@gmail.com</a>
      </p>
    </section>
  );
}